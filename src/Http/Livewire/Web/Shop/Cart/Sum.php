<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop\Cart;

use Livewire\Component;

class Sum extends Component
{
    public $order;

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.shop.cart.sum');
    }
}