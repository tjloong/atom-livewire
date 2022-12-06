<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Livewire\Component;

class Split extends Component
{
    public $document;

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get splits property
     */
    public function getSplitsProperty()
    {
        $splits = model('document')
            ->when(
                model('document')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->when(
                $this->document->splitted_from_id, 
                fn($q, $id) => $q->where('splitted_from_id', $id)->orWhere('id', $id),
                fn($q) => $q->where('splitted_from_id', $this->document->id)->orWhere('id', $this->document->id)
            )
            ->latest('issued_at')
            ->latest('id')
            ->get();

        return $splits->count() > 1 ? $splits : collect();
    }

    /**
     * Open split form modal
     */
    public function openSplitFormModal()
    {
        $this->emitTo(lw('app.document.view.split-form-modal'), 'open');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.split');
    }
}