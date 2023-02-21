<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

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
        breadcrumbs()->home('Plans');
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        return model('plan')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows)
            ->through(fn($plan) => [
                [
                    'column_name' => 'Plan',
                    'column_sort' => 'name',
                    'label' => $plan->name,
                    'href' => route('app.plan.update', [$plan->id]),
                ],

                [
                    'column_name' => 'Trial',
                    'count' => $plan->trial,
                    'uom' => 'day',
                ],
            ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.listing');
    }
}