<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithCart
{
    use WithPopupNotify;

    public $order;

    /**
     * Load order
     */
    public function loadOrder()
    {
        $this->order = model('order')
            ->with('items')
            ->status('pending')
            ->where('ulid', session('shop_order'))
            ->when(user(), fn($q) => $q->where('user_id', user('id')))
            ->first();
        
        $this->countOrderItems();
    }

    /**
     * Count order items
     */
    public function countOrderItems()
    {
        $count = 0;

        if ($this->order) {
            $count = $this->order->items()->sum('qty');
        }

        session(['shop_cart_count' => $count]);
        
        $this->dispatchBrowserEvent('shop-cart-count', $count);
    }

    /**
     * Add to cart
     */
    public function addToCart($data)
    {
        $this->order = $this->order ?? model('order')->create([
            'number' => 'temp',
            'user_id' => user('id'),
        ]);

        session(['shop_order' => (string) $this->order->ulid]);

        $item = $this->order->items()
            ->when(data_get($data, 'product_id'), fn($q, $id) => $q->where('product_id', $id))
            ->when(data_get($data, 'product_variant_id'), fn($q, $id) => $q->where('product_variant_id', $id))
            ->first();

        if ($item) $item->fill(['qty' => $item->qty + data_get($data, 'qty')])->save();
        else $this->order->items()->create($data);

        $this->order->fresh()->touch();

        $this->countOrderItems();
        $this->popup('Added To Cart', 'toast', 'success');
    }

    /**
     * Remove from cart
     */
    public function removeFromCart($id)
    {
        optional($this->order->items()->find($id))->delete();

        if ($this->order->items()->count()) $this->order->fresh()->touch();
        else {
            $this->order->delete();
            $this->clearCartSession();
        }

        $this->loadOrder();
    }

    /**
     * Clear cart session
     */
    public function clearCartSession()
    {       
        session()->forget('shop_order');
        session()->forget('shop_cart_count');

        $this->dispatchBrowserEvent('shop-cart-count', 0);
    }
}