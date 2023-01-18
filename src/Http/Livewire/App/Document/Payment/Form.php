<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Payment;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithPopupNotify;

    public $payment;

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
     * Get paymodes property
     */
    public function getPaymodesProperty()
    {
        return model('document')->enabledBelongsToAccountTrait
            ? account_settings('paymodes', ['Cash'])
            : site_settings('paymodes', ['Cash']);
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

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.payment.form');
    }
}