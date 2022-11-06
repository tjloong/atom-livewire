<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
    use WithTable;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['search' => null], 
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        return model('plan')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.listing');
    }
}