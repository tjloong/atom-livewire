<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['except' => ['search' => null]],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Get roles property
     */
    public function getRolesProperty()
    {
        return model('role')
            ->when(model('role')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->withCount('users')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(50);
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
        return atom_view('app.role.listing');
    }
}