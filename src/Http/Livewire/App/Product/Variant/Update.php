<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Livewire\Component;

class Update extends Component
{
    public $variant;

    /**
     * Mount
     */
    public function mount ($variantId)
    {
        $this->variant = model('product_variant')
            ->when(
                model('product')->enabledHasTenantTrait,
                fn($q) => $q->whereHas('product', fn($q) => $q->belongsToTenant())
            )
            ->findOrFail($variantId);

        breadcrumbs()->push($this->variant->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->variant->delete();
        $this->deleted();
    }

    /**
     * Deleted
     */
    public function deleted()
    {
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
        return atom_view('app.product.variant.update');
    }
}