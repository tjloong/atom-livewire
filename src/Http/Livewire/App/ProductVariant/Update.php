<?php

namespace Jiannius\Atom\Http\Livewire\App\ProductVariant;

use Livewire\Component;

class Update extends Component
{
    /**
     * Mount
     */
    public function mount ($productVariant)
    {
        $this->variant = model('product_variant')->findOrFail($productVariant);

        breadcrumbs()->push($this->variant->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->variant->delete();

        return redirect()->route('app.product.update', [
            'product' => $this->variant->product_id,
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