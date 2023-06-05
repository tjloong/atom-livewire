<?php

namespace Jiannius\Atom\Http\Livewire\Web\Product;

use Livewire\Component;

class View extends Component
{
    public $product;
    public $inputs;

    /**
     * Mount
     */
    public function mount($slug)
    {
        $this->product = model('product')->readable()->where('slug', $slug)->firstOrFail();

        $this->fill([
            'inputs.qty' => 1,
            'inputs.variant_id' => data_get($this->variants, '0.id'),
        ]);
    }

    /**
     * Get currency property
     */
    public function getCurrencyProperty()
    {
        return tenant('settings.default_currency') ?? settings('default_currency');
    }

    /**
     * Get variant property
     */
    public function getVariantProperty()
    {
        return $this->variants->firstWhere('id', data_get($this->inputs, 'variant_id'));
    }

    /**
     * Get variants property
     */
    public function getVariantsProperty()
    {
        if ($this->product->type !== 'variant') return collect();

        return $this->product->variants()
            ->with('image')
            ->status('active')
            ->oldest('seq')
            ->oldest('id')
            ->get();
    }

    /**
     * Get images property
     */
    public function getImagesProperty()
    {
        $images = $this->product->images()
            ->oldest('seq')
            ->oldest('id')
            ->get();

        $this->variants->whereNotNull('image_id')->each(fn($variant) => 
            $images->push($variant->image)
        );

        return $images;
    }

    /**
     * Updated inputs variant id
     */
    public function updatedInputsVariantId()
    {
        $index = $this->images->search(fn($img) => $img->id === $this->variant->image_id);
        
        if (is_numeric($index)) $this->dispatchBrowserEvent('slide-to-image', $index);
    }

    /**
     * Add to cart
     */
    public function addToCart()
    {
        $this->emitTo(atom_lw('web.shop.cart'), 'add', [
            'qty' => data_get($this->inputs, 'qty'),
            'product_id' => $this->product->id,
            'product_variant_id' => data_get($this->inputs, 'variant_id'),
        ]);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.product.view');
    }
}