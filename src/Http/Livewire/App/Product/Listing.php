<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort;

    public $filters = [
        'type' => null,
        'status' => null,
        'product_category' => null,
        'search' => null,
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
    public function getQueryProperty(): mixed
    {
        return model('product')
            ->readable()
            ->with('variants', 'categories')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest('updated_at'));
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
                'name' => 'Category',
                'tags' => $query->categories->pluck('name.'.app()->currentLocale())->toArray(),
            ],
            [
                'name' => 'Price',
                'sort' => 'price',
                'class' => 'text-right',
                'label' => $query->type === 'variant'
                    ? 'variants'
                    : currency($query->price),
            ],
            [
                'name' => 'Status',
                'status' => collect([
                    'yellow' => $query->is_featured ? 'feat' : null,
                    $query->is_active ? 'active' : 'inactive',
                ])->filter()->toArray(),
            ]
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
            ])->toArray(),

            'types' => model('product')->getTypes(),
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
