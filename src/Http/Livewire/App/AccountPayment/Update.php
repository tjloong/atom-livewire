<?php

namespace Jiannius\Atom\Http\Livewire\App\AccountPayment;

use Livewire\Component;

class Update extends Component
{
    public $payment;

    /**
     * Mount
     */
    public function mount($paymentId)
    {
        $this->payment = model('account_payment')
            ->when(
                !auth()->user()->isAccountType('root'), 
                fn($q) => $q->where('account_id', auth()->user()->account_id)
            )
            ->findOrFail($paymentId);

        breadcrumbs()->push('Payment #'.$this->payment->number);
    }

    /**
     * Download
     */
    public function download($doc)
    {
        $filename = 'billing-payment-'.$this->payment->number.'.pdf';
        $path = storage_path($filename);
        $view = view()->exists('pdf.account-payment') 
            ? 'pdf.account-payment' 
            : 'atom::pdf.account-payment';


        pdf($view, [
            'payment' => $this->payment,
            'doc' => $doc,
            'filename' => $filename,
        ])->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.account-payment.update');
    }
}