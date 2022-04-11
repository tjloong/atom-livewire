<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters', 
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
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
            ->paginate(30);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan.listing');
    }
}