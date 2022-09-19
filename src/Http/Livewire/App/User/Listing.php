<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\WithPopupNotify;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
    use WithPopupNotify;

    public $role;
    public $account;
    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'filters' => ['except' => [
            'search' => null,
            'status' => null,
        ]], 
    ];

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        $trashed = (clone $this->query)->status('trashed')->count();

        return array_filter([
            ['slug' => null, 'label' => 'All'],
            $trashed
                ? ['slug' => 'trashed', 'label' => 'Trashed', 'count' => $trashed]
                : null,
        ]);
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('user')
            ->when(
                $this->role, 
                fn($q) => $q->where('role_id', $this->role->id)
            )
            ->when(
                $this->account && auth()->user()->isAccountType('root'), 
                fn($q) => $q->where('account_id', $this->account->id),
                fn($q) => $q->where('account_id', auth()->user()->account_id)
            )
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return $this->query->paginate(50);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Empty trashed
     */
    public function emptyTrashed()
    {
        (clone $this->query)->onlyTrashed()->forceDelete();

        $this->popup('Trash Cleared');
        $this->reset('filters');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.listing');
    }
}