<?php

namespace Jiannius\Atom\Http\Livewire\Web\Thank\Shop;

use Jiannius\Atom\Traits\Livewire\WithCart;
use Livewire\Component;

class Payment extends Component
{
    use WithCart;

    public $payment;

    /**
     * Mount
     */
    public function mount()
    {
        $this->payment = model('payment')->where('ulid', request()->query('payment'))->firstOrFail();
        $this->clearCartSession();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.thank.shop.payment');
    }
}