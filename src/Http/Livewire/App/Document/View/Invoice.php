<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Livewire\Component;

class Invoice extends Component
{
    public $document;

    /**
     * Get invoices property
     */
    public function getInvoicesProperty()
    {
        return model('document')
            ->when(
                model('document')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->where('converted_from_id', $this->document->id)
            ->latest('issued_at')
            ->latest('id')
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.invoice');
    }
}