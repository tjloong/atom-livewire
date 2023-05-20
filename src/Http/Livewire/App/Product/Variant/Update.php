<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;

    public $variant;

    protected $listeners = ['submitted'];

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
     * Submitted
     */
    public function submitted(): mixed
    {
        return $this->popup('Product Variant Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.variant.update');
    }
}