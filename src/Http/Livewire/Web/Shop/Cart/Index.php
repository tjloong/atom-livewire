<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop\Cart;

use Jiannius\Atom\Traits\Livewire\WithCart;
use Livewire\Component;

class Index extends Component
{
    use WithCart;

    protected $listeners = [
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
    public function render()
    {
        return atom_view('web.shop.cart');
    }
}