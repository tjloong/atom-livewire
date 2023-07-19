<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Payment;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $payment;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'payment.paymode' => ['required' => 'Payment method is required.'],
            'payment.currency' => ['nullable'],
            'payment.currency_rate' => ['nullable'],
            'payment.amount' => ['required' => 'Payment amount is required.'],
            'payment.paid_at' => ['nullable'],
            'payment.document_id' => ['required' => 'Unknown document.'],
        ];
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'paymodes' => has_table('tenants') && ($tenant = $this->payment->document->tenant ?? null)
                ? tenant('settings.paymodes', ['Cash'], $tenant)
                : settings('paymodes', ['Cash']),
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->payment->save();
        $this->payment->document->sumTotal();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.payment.form');
    }
}