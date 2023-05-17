<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Illuminate\Database\Eloquent\Collection;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;

    public $type;
    public $header;
    public $maxDepth = 0;

    protected $listeners = [
        'open',
        'delete',
        'refresh' => '$refresh',
    ];

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        if ($this->header) return $this->header;

        return $this->type
            ? str($this->type)->headline()->plural()->toString()
            : 'Labels';
    }

    /**
     * Get labels property
     */
    public function getLabelsProperty(): Collection
    {
        return model('label')
            ->with('children')
            ->readable()
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->whereNull('parent_id')
            ->oldest('seq')
            ->oldest('id')
            ->get();
    }

    /**
     * Sort
     */
    public function sort($data): void
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->popup('Sorted Labels');
    }

    /**
     * Delete
     */
    public function delete($id): void
    {
        optional(model('label')->find($id))->delete();

        $this->emit('refresh');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.label.listing');
    }
}