<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $renew;
    public $sort = 'name,asc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['except' => ['search' => null]], 
    ];

    /**
     * Mount
     */
    public function mount()
    {
        $this->renew = !tier('root') && request()->query('renew')
            ? model('plan_subscription')->readable()->find(request()->query('renew'))
            : null;

        breadcrumbs()->home('Plans');
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
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
            ->with(['prices' => fn($q) => $q->where('country', 
                $this->renew->price->country
                ?? geoip()->getLocation()->iso_code 
                ?? null
            )])
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Plan',
                'column_sort' => 'name',
                'label' => $query->name,
                'href' => route('app.plan.update', [$query->id]),
            ],

            [
                'column_name' => 'Trial',
                'count' => $query->trial,
                'uom' => 'day',
            ],
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.listing');
    }
}