<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Payment;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public $payment;
    public $document;

    /**
     * Mount
     */
    public function mount($documentId, $documentPaymentId)
    {
        $this->authorize('document-payment.update');

        $this->document = model('document')->when(
            model('document')->enabledHasTenantTrait,
            fn($q) => $q->belongsToTenant(),
        )->findOrFail($documentId);

        $this->payment = $this->document->payments()->findOrFail($documentPaymentId);

        breadcrumbs()->push($this->payment->number);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->payment->delete();
        $this->document->sumTotal();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.payment.update');
    }
}