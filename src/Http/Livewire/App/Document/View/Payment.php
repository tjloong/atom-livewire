<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Payment extends Component
{
    use WithPopupNotify;

    public $document;

    /**
     * Get payments property
     */
    public function getPaymentsProperty()
    {
        return model('document_payment')
            ->where('document_id', $this->document->id)
            ->latest('paid_at')
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.payment');
    }
}