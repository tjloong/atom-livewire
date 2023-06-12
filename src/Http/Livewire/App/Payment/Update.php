<?php

namespace Jiannius\Atom\Http\Livewire\App\Payment;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $payment;

    /**
     * Mount
     */
    public function mount($id): void
    {
        $this->payment = model('payment')->findOrFail($id);

        breadcrumbs()->push($this->payment->number);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        if (in_array($this->payment->status, ['failed', 'draft'])) {
            $this->payment->delete();
        }

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.payment.update');
    }
}