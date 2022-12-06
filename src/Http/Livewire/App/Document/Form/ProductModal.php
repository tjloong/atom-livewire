<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Livewire\Component;

class ProductModal extends Component
{
    public $page;
    public $product;
    public $currency;
    public $showCost = false;
    public $filters = ['search' => null];

    protected $listeners = ['open'];

    /**
     * Open
     */
    public function open($currency)
    {
        $this->currency = $currency;
        $this->dispatchBrowserEvent('product-modal-open');
    }

    /**
     * Get products
     */
    public function getProductsProperty()
    {
        return model('product')
            ->when(
                model('product')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->filter($this->filters)
            ->status('active')
            ->toPage($this->page)
            ->through(fn($product) => $this->formatProductOption($product));
    }

    /**
     * Format product option
     */
    public function formatProductOption($product)
    {
        $formatted = [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'type' => $product->type,
            'price' => $this->showCost ? $product->cost : $product->price,
        ];

        if ($product->type === 'variant' && $product->variants) {
            $formatted['variants'] = $product->variants->map(fn($variant) => [
                'id' => $variant->id,
                'name' => $variant->name,
                'code' => $variant->code,
                'price' => $this->showCost ? $variant->cost : $variant->price,
            ]);
        }

        return $formatted;
    }

    /**
     * Select
     */
    public function select($data = null)
    {
        if (!data_get($data, 'product_variant_id')) {
            if ($product = collect($this->products->items())
                ->where('id', data_get($data, 'product_id'))
                ->where('type', 'variant')
                ->first()
            ) {
                return $this->product = $product;
            }
        }

        $this->emitUp('addItem', $data);
        $this->reset('product');
        $this->dispatchBrowserEvent('product-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.product-modal');
    }
}