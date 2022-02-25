<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Team;

class Listing extends Component
{
    use WithPagination;

    public $user;
    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $showHeader = true;
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
        breadcrumb_home('Teams');
    }

    /**
     * Get teams property
     */
    public function getTeamsProperty()
    {
        return Team::withCount('users')
            ->filter($this->filters)
            ->when($this->user, fn($q) => $q->userId($this->user->id))
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
        return view('atom::app.team.listing', [
            'teams' => $this->teams->paginate(30),
        ]);
    }
}