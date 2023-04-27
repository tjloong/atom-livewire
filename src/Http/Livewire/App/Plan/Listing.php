<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $fullpage;
    public $sort = 'name,asc';

    public $filters = [
        'search' => null,
        'country' => null,
        'currency' => null,
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        breadcrumbs()->home('Plans');
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('plan')
            ->readable()
            ->with('prices')
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Plan Code',
                'sort' => 'code',
                'label' => $query->code,
                'href' => route('app.plan.update', [$query->id]),
            ],

            [
                'name' => 'Plan Name',
                'sort' => 'name',
                'label' => $query->name,
                'href' => route('app.plan.update', [$query->id]),
            ],

            [
                'name' => 'Country',
                'class' => 'text-right',
                'label' => $query->country
                    ? data_get(countries()->firstWhere('code', $query->country), 'name')
                    : 'All',
            ],

            [
                'name' => 'Price',
                'class' => 'text-right',
                'tags' => $query->prices
                    ->map(fn($val) => currency($val->amount, $query->currency))
                    ->toArray(),
            ],

            [
                'name' => 'Status',
                'status' => array_filter([
                    $query->is_hidden ? 'hidden' : null,
                    $query->is_active ? 'active' : 'inactive',
                ]),
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.listing');
    }
}