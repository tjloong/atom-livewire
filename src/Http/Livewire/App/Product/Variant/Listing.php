<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Illuminate\Database\Eloquent\Collection;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;

    public $product;
    public $filters = ['search' => null];

    /**
     * Get variants property
     */
    public function getVariantsProperty(): Collection
    {
        return $this->product->variants()
            ->filter($this->filters)
            ->orderBy('is_active', 'desc')
            ->orderBy('seq')
            ->orderBy('id')
            ->get();
    }

    /**
     * Sort
     */
    public function sort($sorted): void
    {
        foreach ($sorted as $seq => $id) {
            model('product_variant')->find($id)->update(['seq' => $seq]);
        }

        $this->popup('Product Variants Sorted');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.variant.listing');
    }
}
