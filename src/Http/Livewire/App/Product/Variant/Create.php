<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Livewire\Component;

class Create extends Component
{
    public $product;
    public $variant;

    /**
     * Mount
     */
    public function mount($productId)
    {
        $this->product = model('product')
            ->when(
                model('product')->enabledHasTenantTrait, 
                fn($q) => $q->belongsToTenant()
            )
            ->findOrFail($productId);

        $this->variant = model('product_variant')->fill([
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
        return atom_view('app.product.variant.create');
    }
}