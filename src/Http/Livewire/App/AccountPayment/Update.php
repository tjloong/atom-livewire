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