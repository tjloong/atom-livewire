<?php

namespace Jiannius\Atom\Http\Livewire\App\Shipping;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        //
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('shipping_rate')
            ->readable()
            ->with('countries')
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Name',
                'sort' => 'name',
                'label' => $query->name,
                'href' => route('app.shipping.update', [$query->id]),
            ],
            [
                'name' => 'Price',
                'sort' => 'price',
                'amount' => $query->price,
            ],
            [
                'status' => $query->is_active ? null : 'inactive',
                'class' => 'text-right',
            ],
            [
                'name' => 'Country',
                'tags' => $query->countries->pluck('name')->map(fn($val) => countries($val.'.name'))->toArray(),
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.shipping.listing');
    }
}
