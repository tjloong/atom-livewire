<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort = 'updated_at,desc';
    public $filters = [
        'type' => null,
        'status' => null,
        'product_category' => null,
        'search' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'type' => null,
            'status' => null,
            'product_category' => null,
            'search' => null,
        ]],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Products');
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('product')
            ->readable()
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Code',
                'column_sort' => 'code',
                'label' => $query->code ?? '--',
                'href' => $query->code ? route('app.product.update', [$query->id]) : null,
            ],
            [
                'column_name' => 'Product',
                'column_sort' => 'name',
                'label' => $query->name,
                'href' => route('app.product.update', [$query->id]),
                'small' => $query->type === 'variant'
                    ? __(':count '.str('variant')->plural($query->variants->count()), [
                        'count' => $query->variants->count()
                    ])
                    : null
            ],
            [
                'active' => $query->is_active,
            ],
            [
                'column_name' => 'Category',
                'tags' => $query->categories->pluck('name.'.app()->currentLocale()),
            ],
            [
                'column_name' => 'Price',
                'column_sort' => 'price',
                'column_class' => 'text-right',
                'amount' => $query->price,
                'class' => 'text-right',
            ],
        ];
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
                ->when(
                    model('label')->enabledHasTenantTrait,
                    fn($q) => $q->belongsToTenant(),
                )
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
