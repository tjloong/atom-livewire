<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $renew;
    public $fullpage;
    public $sort = 'name,asc';

    public $filters = [
        'search' => null
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->renew = !tier('root') && request()->query('renew')
            ? model('plan_subscription')->readable()->find(request()->query('renew'))
            : null;

        if ($this->fullpage = current_route('app.plan.listing')) {
            breadcrumbs()->home('Plans');
        }
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('plan')
            ->readable()
            ->when($this->renew, fn($q) => $q
                ->whereIn('id', 
                    collect([$this->renew->price->plan_id])
                        ->concat($this->renew->price->plan->upgradables->pluck('id')->toArray())
                        ->concat($this->renew->price->plan->downgradables->pluck('id')->toArray())
                        ->unique()
                        ->toArray()
                )
            )
            // ->with(['prices' => fn($q) => $q->where('country', 
            //     $this->renew->price->country
            //     ?? geoip()->getLocation()->iso_code 
            //     ?? null
            // )])
            ->withCount('prices')
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
                'label' => $query->name,
                'href' => route('app.plan.update', [$query->id]),
            ],

            [
                'name' => 'Trial',
                'class' => 'text-right',
                'count' => $query->trial,
                'uom' => 'day',
            ],

            [
                'name' => 'Prices',
                'class' => 'text-right',
                'count' => $query->prices_count,
                'uom' => 'price',
            ],

            [
                'name' => 'Status',
                'status' => $query->is_active ? 'active' : 'inactive',
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