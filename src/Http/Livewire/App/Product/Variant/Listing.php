<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;

    public $product;
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['except' => ['search' => null]],
    ];

    /**
     * Get variants property
     */
    public function getVariantsProperty()
    {
        return $this->product->variants()
            ->filter($this->filters)
            ->orderBy('seq')
            ->get();
    }

    /**
     * Sort
     */
    public function sort($sorted)
    {
        foreach ($sorted as $seq => $id) {
            model('product_variant')->find($id)->update(['seq' => $seq]);
        }

        $this->popup('Product Variants Sorted');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.product.variant.listing');
    }
}
