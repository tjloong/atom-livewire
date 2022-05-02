<?php

namespace Jiannius\Atom\Http\Livewire\Billing\AccountPayment;

use Livewire\Component;

class Update extends Component
{
    public $accountPayment;

    /**
     * Mount
     */
    public function mount($accountPayment)
    {
        $this->accountPayment = model('account_payment')->findOrFail($accountPayment);

        if ($this->isFullpage) breadcrumbs()->home('#'.$this->accountPayment->number);
    }

    /**
     * Get is fullpage property
     */
    public function getIsFullpageProperty()
    {
        return current_route('app.account-payment.update');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.account-payment.update')->layout('layouts.billing');
    }
}