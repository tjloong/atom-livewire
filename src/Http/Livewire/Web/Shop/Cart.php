<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop;

use Livewire\Component;

class Cart extends Component
{
    public $items = [];

    protected $listeners = ['add'];

    /**
     * Updated items
     */
    public function updatedItems()
    {
        session(['cart' => collect($this->items)
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
        if (session('cart')) {
            $items = collect(session('cart'))->map(function($item) {
                if (
                    ($productId = data_get($item, 'product_id'))
                    && !data_get($item, 'product')
                ) {
                    $item['product'] = model('product')->readable()->find($productId);
                }

                if (
                    ($variantId = data_get($item, 'product_variant_id'))
                    && !data_get($item, 'product_variant')
                ) {
                    $item['product_variant'] = model('product_variant')->find($variantId);
                }

                if (!data_get($item, 'amount')) {
                    $item['amount'] = data_get($item, 'qty') 
                        * (data_get($item, 'product_variant.price') ?? data_get($item, 'product.price'));
                }

                return $item;
            })->toArray();

            $this->items = $items;

            session(['cart' => $items]);
        }
        elseif (user()) {
            $this->items = model('cart')
                ->with(['product', 'variant'])
                ->where('user_id', user('id'))
                ->latest()
                ->get()
                ->toArray();

            session(['cart' => $this->items]);
        }
        else {
            $this->items = [];
        }

        $this->dispatchBrowserEvent('cart-count', collect($this->items)->sum('qty'));
    }

    /**
     * Add
     */
    public function add($data)
    {
        if (user()) {
            $item = model('cart')
                ->where('user_id', user('id'))
                ->when(data_get($data, 'product_id'), fn($q, $id) => $q->where('product_id', $id))
                ->when(data_get($data, 'product_variant_id'), fn($q, $id) => $q->where('product_variant_id', $id))
                ->first();

            if ($item) $item->fill(['qty' => $item->qty + data_get($data, 'qty')])->save();
            else model('cart')->create(array_merge($data, ['user_id' => user('id')]));

            session()->forget('cart');
        }
        else {
            $items = collect(session('cart'));

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

            session(['cart' => $items->values()->all()]);
        }

        $this->setItems();
        $this->dispatchBrowserEvent('cart-open');
    }

    /**
     * Remove
     */
    public function remove($i)
    {
        if (user()) {
            $item = collect($this->items)->get($i);

            optional(
                model('cart')->where('user_id', user('id'))->find(data_get($item, 'id'))
            )->delete();

            session()->forget('cart');
        }
        else {
            $items = collect($this->items);
            $items->splice($i, 1);
    
            session(['cart' => $items->values()->all()]);
        }

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