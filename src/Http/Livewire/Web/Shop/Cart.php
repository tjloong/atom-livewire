<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop;

use Livewire\Component;

class Cart extends Component
{
    public $items = [];

    protected $listeners = ['add', 'open'];

    /**
     * Updated items
     */
    public function updatedItems()
    {
        session(['cart-items' => collect($this->items)
            ->map(fn($item) => 
                collect($item)->only(
                    'qty',
                    'product_id',
                    'product_variant_id',
                )->toArray()
            )
            ->toArray()
        ]);

        $this->setItems();
    }

    /**
     * Set items
     */
    public function setItems()
    {
        if (!session('cart-items')) $this->items = [];

        $products = ($id = collect(session('cart-items'))->pluck('product_id')->toArray())
            ? model('product')->readable()->whereIn('id', $id)->get()
            : collect();

        $variants = ($id = collect(session('cart-items'))->pluck('product_variant_id')->toArray())
            ? model('product_variant')->whereIn('id', $id)->get()
            : collect();

        $this->items = collect(session('cart-items'))->map(function ($item) use ($products, $variants) {
            $product = $products->firstWhere('id', data_get($item, 'product_id'));
            $variant = $variants->firstWhere('id', data_get($item, 'product_variant_id'));
            $amount = data_get($item, 'qty') * ($variant ?? $product)->price;

            return array_merge($item, [
                'product' => $product,
                'product_variant' => $variant,
                'amount' => $amount,
            ]);
        })->values()->all();

        $this->dispatchBrowserEvent('cart-count', collect(session('cart-items'))->sum('qty'));
    }

    /**
     * Open
     */
    public function open()
    {
        $this->setItems();
        $this->dispatchBrowserEvent('cart-open');
    }

    /**
     * Add
     */
    public function add($data)
    {
        $items = collect(session('cart-items', []));
        $search = $items->search(function($item) use ($data) {
            $matchProduct = data_get($item, 'product_id') === data_get($data, 'product_id');
            $matchVariant = data_get($item, 'product_variant_id') === data_get($data, 'product_variant_id');

            return data_get($data, 'product_variant_id')
                ? $matchProduct && $matchVariant
                : $matchProduct;
        });

        if (is_numeric($search)) {
            $items->put($search, array_merge(
                $items->get($search),
                ['qty' => data_get($items->get($search), 'qty') + data_get($data, 'qty')],
            ));
        }
        else $items->push($data);

        session(['cart-items' => $items]);

        $this->open();
    }

    /**
     * Remove
     */
    public function remove($i)
    {
        $items = collect($this->items);
        $items->splice($i, 1);

        session(['cart-items' => $items->values()->all()]);

        $this->setItems();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('web.shop.cart');
    }
}