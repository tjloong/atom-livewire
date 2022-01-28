<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'name';
    public $sortOrder = 'asc';

    protected $queryString = [
        'search' => ['except' => ''], 
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
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.user.listing', ['users' => $this->getUsers()]);
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

    /**
     * Get users
     * 
     * @return Collection
     */
    public function getUsers()
    {
        return User::query()
            ->where('email', '<>', User::ROOT_EMAIL)
            ->when($this->search, fn($q) => $q->search($this->search))
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(30);
    }
}