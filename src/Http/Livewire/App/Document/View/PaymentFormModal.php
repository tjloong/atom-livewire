<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class PaymentFormModal extends Component
{
    use WithPopupNotify;

    public $payment;

    protected $listeners = ['open'];

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'payment.paymode' => 'required',
            'payment.currency' => 'nullable',
            'payment.currency_rate' => 'nullable|numeric',
            'payment.amount' => 'required|numeric',
            'payment.paid_at' => 'nullable|date',
            'payment.document_id' => 'required',
        ];
    }

    /**
     * Validation message
     */
    public function messages()
    {
        return [
            'payment.amount.required' => __('Payment amount is required.'),
            'payment.paymode.required' => __('Payment method is required.'),
            'payment.document_id.required' => __('Unknown document.'),
        ];
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        if (!$this->payment) return;
        if ($this->payment->id) return 'Update Payment';

        return [
            'invoice' => 'Receive Payment',
            'bill' => 'Issue Payment',
        ][$this->payment->document->type];
    }

    /**
     * Get paymodes property
     */
    public function getPaymodesProperty()
    {
        return model('document')->enabledBelongsToAccountTrait
            ? account_settings('paymodes', ['Cash'])
            : site_settings('paymodes', ['Cash']);
    }

    /**
     * Open
     */
    public function open($data)
    {
        $this->payment = data_get($data, 'id')
            ? model('document_payment')->findOrFail(data_get($data, 'id'))
            : model('document_payment')->fill($data);

        $this->dispatchBrowserEvent('payment-form-modal-open');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->payment->save();
        $this->payment->document->sumTotal();

        $this->emit('refresh');
        $this->popup('Payment Updated.');
        $this->dispatchBrowserEvent('payment-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.payment-form-modal');
    }
}