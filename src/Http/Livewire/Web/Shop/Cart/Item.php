<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop\Cart;

use Livewire\Component;

class Item extends Component
{
    public $small;
    public $readonly;
    public $order;
    public $items;

    protected $rules = ['items.*.qty' => 'nullable'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->items = optional($this->order)->items;
    }

    /**
     * Updated items
     */
    public function updatedItems()
    {
        $this->items->each(fn($item) => $item->save());
        $this->order->touch();

        $this->emitUp('cartUpdated');
    }


    /**
     * Remove
     */
    public function remove($id)
    {
        $this->emitUp('removeFromCart', $id);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('web.shop.cart.item');
    }
}