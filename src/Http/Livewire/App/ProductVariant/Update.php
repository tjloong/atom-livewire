<?php

namespace Jiannius\Atom\Http\Livewire\App\ProductVariant;

use Livewire\Component;

class Update extends Component
{
    public $productVariant;

    /**
     * Mount
     */
    public function mount ($productVariantId)
    {
        $this->productVariant = model('product_variant')->findOrFail($productVariantId);

        breadcrumbs()->push($this->productVariant->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->productVariant->delete();

        return redirect()->route('app.product.update', [
            'product' => $this->productVariant->product_id,
            'tab' => 'variants',
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product-variant.update');
    }
}