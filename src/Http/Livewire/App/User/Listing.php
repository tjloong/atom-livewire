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

    protected $breadcrumb = ['home' => 'Users'];

    protected $queryString = [
        'filters', 
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
        breadcrumb(['home' => 'Users']);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Get users
     */
    public function getUsers()
    {
        return User::query()
            ->where('email', '<>', User::ROOT_EMAIL)
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(30);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.listing', ['users' => $this->getUsers()]);
    }
}