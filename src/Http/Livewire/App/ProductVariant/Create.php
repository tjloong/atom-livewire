<?php

namespace Jiannius\Atom\Http\Livewire\App\ProductVariant;

use Livewire\Component;

class Create extends Component
{
    public $product;
    public $productVariant;

    /**
     * Mount
     */
    public function mount($productId)
    {
        $this->product = model('product')
            ->when(
                model('product')->enabledBelongsToAccountTrait, 
                fn($q) => $q->belongsToAccount()
            )
            ->findOrFail($productId);

        $this->productVariant = model('product_variant')->fill([
            'is_default' => false,
            'is_active' => true,
            'product_id' => $this->product->id,
        ]);
    
        breadcrumbs()->push('Create Product Variant');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product-variant.create');
    }
}