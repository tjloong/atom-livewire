<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Payment;

use Livewire\Component;

class Update extends Component
{
    public $payment;

    /**
     * Mount
     */
    public function mount($paymentId): void
    {
        $this->payment = model('plan_payment')
            ->when(!tier('root'), fn($q) => $q->where('user_id', user('id')))
            ->findOrFail($paymentId);

        breadcrumbs()->push('Payment #'.$this->payment->number);
    }

    /**
     * Download
     */
    public function download($doc): mixed
    {
        $filename = 'plan-payment-'.$this->payment->number.'.pdf';
        $path = storage_path($filename);
        $view = view()->exists('pdf.plan-payment') 
            ? 'pdf.plan-payment' 
            : 'atom::pdf.plan-payment';


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
    public function render(): mixed
    {
        return atom_view('app.plan.payment.update');
    }
}