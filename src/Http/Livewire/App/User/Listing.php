<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use App\Models\User;
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
        breadcrumb_home('Users');
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return User::query()
            ->where('email', '<>', User::ROOT_EMAIL)
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
        return view('atom::app.user.listing', ['users' => $this->users->paginate(30)]);
    }
}