<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['search' => null], 
        'sortBy' => ['except' => 'updated_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Get pages property
     */
    public function getPagesProperty()
    {
        return model('page')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->get();
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
        return view('atom::app.page.listing');
    }
}