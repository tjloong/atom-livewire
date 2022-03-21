<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

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
        breadcrumbs()->home('Users');
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return model('user')
            ->whereHas('account', fn($q) => $q->where('accounts.type', '<>', 'signup'))
            ->when(!auth()->user()->isRoot(), fn($q) => $q->where('account_id', auth()->user()->account_id))
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