<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Payment;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public $payment;
    public $document;

    /**
     * Mount
     */
    public function mount($documentId)
    {
        $this->authorize('document-payment.create');

        $this->document = model('document')->when(
            model('document')->enabledBelongsToAccountTrait,
            fn($q) => $q->belongsToAccount(),
        )->findOrFail($documentId);

        $this->payment = model('document-payment')->fill([
            'currency' => $this->document->currency,
            'currency_rate' => $this->document->currency_rate,
            'amount' => (
                $this->document->splitted_total 
                ?? $this->document->grand_total
                ) - $this->document->paid_total,
            'document_id' => $this->document->id,
            'paid_at' => today(),
        ]);

        breadcrumbs()->push('Create Payment');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.payment.create');
    }
}