<?php

namespace App\Http\Livewire\App\Team;

use App\Models\Team;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $user;
    public $search;
    public $fullmode;
    public $sortBy = 'name';
    public $sortOrder = 'asc';

    protected $queryString = [
        'search', 
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     * 
     * @return void
     */
    public function mount()
    {
        $this->fullmode = current_route() === 'team.listing';
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.team.listing', [
            'teams' => Team::withCount('users')
                ->when($this->search, fn($q) => $q->search($this->search))
                ->when($this->user, fn($q) => $q->userId($this->user->id))
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