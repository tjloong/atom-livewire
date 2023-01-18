<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Livewire\Component;

class Info extends Component
{
    public $inputs;
    public $document;
    public $settings;

    protected $rules = ['document.type' => 'required'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->inputs = [
            'prefix' => $this->document->prefix,
            'postfix' => $this->document->postfix,
            'reference' => $this->document->reference,
            'payterm' => $this->document->payterm,
            'description' => $this->document->description,
            'issued_at' => $this->document->issued_at,
            'delivered_at' => $this->document->delivered_at,
            'converted_from_id' => $this->document->converted_from_id,
            'data' => json_decode(json_encode($this->document->data), true),
        ];
    }

    /**
     * Updated inputs
     */
    public function updatedInputs()
    {
        $this->emitUp('setDocument', $this->inputs);
    }

    /**
     * Get convert from documents
     */
    public function getConvertFromDocuments($search = null, $page = 1, $sel = [])
    {
        return model('document')
            ->when(
                model('document')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->where(fn($q) => $q
                ->when($search, fn($q) => $q->filter(['search' => $search]))
                ->orWhereIn('id', $sel)
                ->orWhere('type', [
                    'invoice' => 'quotation',
                    'bill' => 'purchase-order',
                    'delivery-order' => 'invoice',
                ][$this->document->type])            
            )
            ->when($this->document->contact_id, fn($q, $id) => $q->where('contact_id', $id))
            ->latest('issued_at')
            ->latest('id')
            ->toPage($page)
            ->through(fn($document) => [
                'value' => $document->id,
                'label' => $document->number,
                'small' => $document->name,
                'remark' => currency($document->splitted_total ?? $document->grand_total, $document->currency),
            ])
            ->toArray();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.info');
    }
}