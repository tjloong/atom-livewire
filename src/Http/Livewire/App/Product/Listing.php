<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
    use WithTable;

    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filters = [
        'type' => null,
        'status' => null,
        'product_category' => null,
        'search' => null,
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'filters' => ['except' => [
            'type' => null,
            'status' => null,
            'product_category' => null,
            'search' => null,
        ]],
        'sortBy' => ['except' => 'updated_at'],
        'sortOrder' => ['except' => 'desc'],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Products');
    }

    /**
     * Get products property
     */
    public function getProductsProperty()
    {
        return model('product')
            ->belongsToAccount()
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'statuses' => collect(['active', 'inactive'])->map(fn($val) => [
                'value' => $val,
                'label' => str()->headline($val),
            ]),

            'types' => model('product')->getTypes(),

            'product_categories' => model('label')
                ->belongsToAccount()
                ->where('type', 'product-category')
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * Get has sold column property
     */
    public function getHasSoldColumnProperty()
    {
        if ($product = $this->products->first()) {
            return in_array('sold', array_keys($product->toArray()));
        }

        return false;
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.product.listing');
    }
}
