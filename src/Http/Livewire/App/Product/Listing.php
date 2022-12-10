<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
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
            ->when(
                model('product')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows)
            ->through(fn($product) => [
                [
                    'column_name' => 'Code',
                    'column_sort' => 'code',
                    'label' => $product->code,
                    'href' => route('app.product.update', [$product->id]),
                ],
                [
                    'column_name' => 'Product',
                    'column_sort' => 'name',
                    'label' => $product->name,
                    'href' => route('app.product.update', [$product->id]),
                    'small' => $product->type === 'variant'
                        ? __(':count '.str('variant')->plural($product->variants->count()), [
                            'count' => $product->variants->count()
                        ])
                        : null
                ],
                [
                    'active' => $product->is_active,
                ],
                [
                    'column_name' => 'Category',
                    'tags' => $product->categories->pluck('name.'.app()->currentLocale()),
                ],
                [
                    'column_name' => 'Price',
                    'column_sort' => 'price',
                    'column_class' => 'text-right',
                    'amount' => $product->price,
                    'class' => 'text-right',
                ],
            ]);
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
     * Render
     */
    public function render()
    {
        return atom_view('app.product.listing');
    }
}
