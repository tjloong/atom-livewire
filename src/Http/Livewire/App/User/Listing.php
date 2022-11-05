<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
    use WithPopupNotify;
    use WithTable;

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
     * Get trashed count property
     */
    public function getTrashedCountProperty()
    {
        return (clone $this->query)->status('trashed')->count();
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
        return $this->query->paginate($this->maxRows);
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
        return atom_view('app.user.listing');
    }
}