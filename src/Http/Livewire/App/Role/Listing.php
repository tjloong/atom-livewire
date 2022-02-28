<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Role;

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
        breadcrumbs()->home('Roles');
    }

    /**
     * Get roles property
     */
    public function getRolesProperty()
    {
        return Role::query()
            ->withCount('users')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder);
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
        return view('atom::app.role.listing', [
            'roles' => $this->roles->paginate(30),
        ]);
    }
}