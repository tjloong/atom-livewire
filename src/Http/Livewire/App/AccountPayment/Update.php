<?php

namespace Jiannius\Atom\Http\Livewire\App\AccountPayment;

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
        breadcrumbs()->home('#'.$this->accountPayment->number);
    }

    /**
     * Download
     */
    public function download($doc)
    {
        return redirect()->route('__pdf', [
            'model' => 'account-payment',
            'find' => $this->accountPayment->id,
            'doc' => $doc,
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        $view = view('atom::app.account-payment.update');

        return current_route('billing*') ? $view->layout('layouts.billing') : $view;
    }
}