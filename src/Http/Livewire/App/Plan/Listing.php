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
            ->withCount('subscriptions')
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Plan',
                'sort' => 'name',
                'label' => $query->code,
                'small' => $query->name,
                'href' => route('app.plan.update', [$query->id]),
            ],

            [
                'name' => 'Valid',
                'label' => str($query->valid ?? 'forever')->headline(),
            ],

            [
                'name' => 'Country',
                'label' => $query->country
                    ? data_get(countries()->firstWhere('code', $query->country), 'name')
                    : 'All',
            ],

            [
                'name' => 'Currency',
                'label' => $query->currency ?? '--',
            ],

            [
                'name' => 'Price',
                'class' => 'text-right',
                'amount' => $query->price ?? '--',
            ],

            [
                'name' => 'Subscriptions',
                'class' => 'text-right',
                'count' => $query->subscriptions_count,
                'uom' => 'subscription',
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