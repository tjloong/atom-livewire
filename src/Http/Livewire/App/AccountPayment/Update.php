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
        $this->accountPayment = model('account_payment')
            ->when(auth()->user()->isAccountType('signup'), 
                fn($q) => $q->where('account_id', auth()->user()->account_id)
            )
            ->findOrFail($accountPayment);

        breadcrumbs()->push('Payment #'.$this->accountPayment->number);
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
        return atom_view('app.account-payment.update');
    }
}