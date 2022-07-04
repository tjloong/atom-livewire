<?php

namespace Jiannius\Atom\Http\Livewire\App\ProductVariant;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $product;
    public $filters = ['search' => ''];

    protected $queryString = [
        'page' => ['except' => 1],
        'filters' => ['except' => ['search' => '']],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Get product variants property
     */
    public function getProductVariantsProperty()
    {
        return $this->product->productVariants()
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

        $this->dispatchBrowserEvent('toast', ['message' => 'Product Variants Sorted', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product-variant.listing');
    }
}
