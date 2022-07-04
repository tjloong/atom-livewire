<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filters = [
        'type' => '',
        'status' => '',
        'product_category' => '',
        'search' => '',
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'filters' => ['except' => [
            'type' => '',
            'status' => '',
            'product_category' => '',
            'search' => '',
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
        return model('product')->belongsToAccount()
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(30);
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

            'types' => model('product')->getTypes()->map(fn($val) => [
                'value' => $val,
                'label' => str()->headline($val),
            ]),

            'product_categories' => model('label')->where('type', 'product-category')
                ->belongsToAccount()
                ->orderBy('name')
                ->selectRaw('id as value, name as label')
                ->get(),
        ];
    }

    /**
     * Get has sold column property
     */
    public function getHasSoldColumnProperty()
    {
        return in_array('sold', array_keys($this->products->first()->toArray()));
    }

    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->reset('filters');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product.listing');
    }
}
