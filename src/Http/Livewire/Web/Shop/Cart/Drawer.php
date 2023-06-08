<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop\Cart;

use Jiannius\Atom\Traits\Livewire\WithCart;
use Livewire\Component;

class Drawer extends Component
{
    use WithCart;

    public $order;

    protected $listeners = [
        'loadOrder',
        'addToCart',
        'removeFromCart',
        'cartUpdated' => 'countOrderItems',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        $this->loadOrder();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.shop.cart.drawer');
    }
}