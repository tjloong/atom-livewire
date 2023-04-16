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
    public function mount($paymentId)
    {
        $this->payment = model('document-payment')
            ->whereHas('document', fn($q) => $q->readable())
            ->findOrFail($paymentId);

        $this->authorize($this->payment->document->type.'-payment.update');

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