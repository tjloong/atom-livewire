<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Illuminate\Contracts\Database\Eloquent\Builder;
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
        'filters',
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        breadcrumbs()->home('Products');
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('product')
            ->readable()
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Code',
                'sort' => 'code',
                'label' => $query->code ?? '--',
                'href' => $query->code ? route('app.product.update', [$query->id]) : null,
            ],
            [
                'name' => 'Product',
                'sort' => 'name',
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
                'name' => 'Category',
                'tags' => $query->categories->pluck('name.'.app()->currentLocale()),
            ],
            [
                'name' => 'Price',
                'sort' => 'price',
                'class' => 'text-right',
                'amount' => $query->price,
            ],
        ];
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'statuses' => collect(['active', 'inactive'])->map(fn($val) => [
                'value' => $val,
                'label' => str()->headline($val),
            ]),

            'types' => model('product')->getTypes(),

            'product_categories' => model('label')
                ->readable()
                ->where('type', 'product-category')
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.listing');
    }
}
