<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Livewire\Component;

class Update extends Component
{
    public $productVariant;

    /**
     * Mount
     */
    public function mount ($productVariantId)
    {
        $this->productVariant = model('product_variant')
            ->when(
                model('product')->enabledBelongsToAccountTrait,
                fn($q) => $q->whereHas('product', fn($q) => $q->belongsToAccount())
            )
            ->findOrFail($productVariantId);

        breadcrumbs()->push($this->productVariant->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->productVariant->delete();
        $this->deleted();
    }

    /**
     * Deleted
     */
    public function deleted()
    {
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
        return view('atom::app.product.variant.update');
    }
}