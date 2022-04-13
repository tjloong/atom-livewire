<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $account;
    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'page' => ['except' => 1],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'filters' => ['except' => ['search' => '']], 
    ];

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->account) breadcrumbs()->home('Users');
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return model('user')
            ->when($this->account,
                fn($q) => $q->whereHas('account', fn($q) => $q->where('account_id', $this->account->id)),
                fn($q) => $q->whereHas('account', fn($q) => $q->whereIn('accounts.type', ['root', 'system']))
            )
            ->when(!auth()->user()->isAccountType('root'), fn($q) => $q->where('account_id', auth()->user()->account_id))
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(30);
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
        return view('atom::app.user.listing');
    }
}