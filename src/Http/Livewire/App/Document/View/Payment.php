<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Payment extends Component
{
    use WithPopupNotify;

    public $document;

    protected $listeners = ['refresh' => '$refresh'];

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
     * Open payment form modal
     */
    public function openPaymentFormModal($id = null)
    {
        $this->emitTo(
            lw('app.document.view.payment-form-modal'), 
            'open', 
            $id ? ['id' => $id] : [
                'currency' => $this->document->currency,
                'currency_rate' => $this->document->currency_rate,
                'amount' => (
                    $this->document->splitted_total 
                    ?? $this->document->grand_total
                 ) - $this->document->paid_total,
                'document_id' => $this->document->id,
                'paid_at' => today(),
            ],
        );
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        optional($this->payments->firstWhere('id', $id))->delete();

        $this->document->sumTotal();
        $this->emit('refresh');
        $this->popup('Payment Deleted.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.payment');
    }
}