<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Livewire\Component;

class ProductModal extends Component
{
    public $item;
    public $page;
    public $product;
    public $currency;
    public $showCost = false;
    public $filters = ['search' => null];

    protected $listeners = ['open'];

    /**
     * Open
     */
    public function open($item)
    {
        if (!$item) return;

        $this->item = $item;
        $this->dispatchBrowserEvent('product-modal-open');
    }

    /**
     * Get products
     */
    public function getProductsProperty()
    {
        return model('product')
            ->readable()
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
            'taxes' => $product->taxes,
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

        $this->setProductToItem($data);
        $this->reset('product');
        $this->dispatchBrowserEvent('product-modal-close');
    }

    /**
     * Set product to item
     */
    public function setProductToItem($data)
    {
        $list = collect($this->products->items());
        $product = $list->firstWhere('id', data_get($data, 'product_id'));
        $variant = optional(data_get($product, 'variants'))->firstWhere('id', data_get($data, 'product_variant_id'));
        $qty = data_get($this->item, 'qty', 1);
        $amount = data_get($variant ?? $product, 'price');
        $taxes = data_get($product, 'taxes')->map(fn($tax) => [
            'id' => $tax->id,
            'label' => $tax->label,
            'amount' => $tax->calculate($amount) * $qty,
        ]);

        $this->emitTo(
            lw('app.document.form.item'), 
            'setProduct:'.(data_get($this->item, 'id') ?? data_get($this->item, 'ulid')),
            array_merge($this->item, [
                'name' => collect([data_get($product, 'name'), data_get($variant, 'name')])
                    ->filter()
                    ->whenNotEmpty(fn($name) => $name->join(' - ')),

                'description' => data_get($product, 'description'),
                'qty' => $qty,
                'amount' => $amount,
                'taxes' => $taxes,
                'product_id' => data_get($product, 'id'),
                'product_variant_id' => data_get($variant, 'id'),
            ])
        );
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.product-modal');
    }
}