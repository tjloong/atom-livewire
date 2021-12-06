<?php

namespace App\Http\Livewire\App\Role;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'name';
    public $sortOrder = 'asc';

    protected $queryString = [
        'search', 
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.role.listing', [
            'roles' => Role::query()
                ->withCount('users')
                ->when($this->search, fn($q) => $q->search($this->search))
                ->orderBy($this->sortBy, $this->sortOrder)
                ->paginate(30),
        ]);
    }

    /**
     * Updating search property
     * 
     * @return void
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
}