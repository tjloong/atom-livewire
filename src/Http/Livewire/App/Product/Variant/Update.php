<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Livewire\Component;

class Update extends Component
{
    public $variant;

    /**
     * Mount
     */
    public function mount ($variantId): void
    {
        $this->variant = model('product_variant')->readable()->findOrFail($variantId);

        breadcrumbs()->push($this->variant->name);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->variant->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.variant.update');
    }
}