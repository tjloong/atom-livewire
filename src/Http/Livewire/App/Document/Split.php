<?php

namespace Jiannius\Atom\Http\Livewire\App\Document;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Split extends Component
{
    use WithPopupNotify;

    public $splits;
    public $master;
    public $document;

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'splits.*' => 'nullable',
        ];
    }

    /**
     * Mount
     */
    public function mount($documentId)
    {
        $this->document = model('document')->readable()->findOrFail($documentId);
        $this->splits = collect();
        $this->master = $this->document->splittedFrom ?? $this->document;

        $children = $this->master->splits;

        if ($children->count()) {
            $this->add($this->master);
            $children->each(fn($child) => $this->add($child));
        }
        else {
            $this->add($this->master, 50);
        }

        breadcrumbs()->push('Split Invoice');
    }

    /**
     * Updated splits
     */
    public function updatedSplits()
    {
        $this->updateAmount();
    }

    /**
     * Add split
     */
    public function add($document = null, $percentage = null)
    {
        $this->resetValidation();

        if ($document) {
            $this->splits->push([
                'percentage' => $percentage ?? (($document->splitted_total/$this->document->grand_total)*100),
                'amount' => $document->splitted_total,
                'issued_at' => $document->issued_at,
                'document_id' => $document->id,
                'document_number' => $document->number,
                'document_status' => $document->status,
            ]);
        }
        else {
            $this->splits->push([
                'percentage' => 100 - $this->splits->sum('percentage'),
                'issued_at' => today(),
            ]);
        }

        $this->updateAmount();
    }

    /**
     * Update amount
     */
    public function updateAmount()
    {
        $this->splits = $this->splits->map(fn($split) => array_merge($split, [
            'percentage' => round(data_get($split, 'percentage'), 2),
            'disabled' => data_get($split, 'document_status') === 'paid',
            'amount' => $this->document->grand_total * (
                round(data_get($split, 'percentage'), 2)/100
            ),
        ]));
    }

    /**
     * Remove
     */
    public function remove($i)
    {
        $this->resetValidation();
        $this->splits = $this->splits->reject(fn($val, $key) => $key === $i)->values();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();

        if ($this->splits->sum('percentage') !== 100) return $this->addError('percentage', 'Sum of splits must be 100%');

        $ids = $this->splits->pluck('document_id')->filter()->values()->all();
        if ($ids) $this->master->fresh()->splits()->whereNotIn('id', $ids)->delete();

        foreach ($this->splits as $split) {
            if ($document = model('document')->find(data_get($split, 'document_id'))) {
                $document->fill([
                    'splitted_total' => data_get($split, 'amount'),
                    'data' => array_merge((array)$document->data, ['percentage' => data_get($split, 'percentage')]),
                    'issued_at' => data_get($split, 'issued_at') ?? $document->issued_at,
                ])->save();
            }
            else {
                $document = $this->master->replicate();
                $document->fill([
                    'rev' => ($this->master->splits()->max('rev') ?? 0) + 1,
                    'splitted_total' => data_get($split, 'amount'),
                    'data' => array_merge((array)$this->master->data, ['percentage' => data_get($split, 'percentage')]),
                    'issued_at' => data_get($split, 'issued_at') ?? today(),
                    'splitted_from_id' => $this->master->id,
                    'created_by' => auth()->id(),
                ])->save();
            }
        }

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.split');
    }
}