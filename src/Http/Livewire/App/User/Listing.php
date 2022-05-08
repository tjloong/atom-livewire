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
    public $filters = [
        'search' => '',
        'status' => 'all',
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'filters' => ['except' => [
            'search' => '',
            'status' => 'all', 
        ]], 
    ];

    /**
     * Mount
     */
    public function mount()
    {
        if ($this->isFullpage = current_route('app.user.listing')) {
            breadcrumbs()->home('Users');
        }
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        $tabs = collect([['slug' => 'all', 'label' => 'All']]);
        $trashed = (clone $this->query)->status('trashed')->count();

        if ($trashed) $tabs->push(['slug' => 'trashed', 'label' => __('Trashed').'('.$trashed.')']);

        return $tabs->count() > 1 ? $tabs : null;
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('user')
            ->when($this->account, 
                fn($q) => $q->where('account_id', $this->account->id),
                fn($q) => $q->when(auth()->user()->isAccountType('root'), 
                    fn($q) => $q->whereHas('account', fn($q) => $q->whereIn('accounts.type', ['root', 'system'])),
                    fn($q) => $q->where('account_id', auth()->user()->account_id)
                )
            )
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return $this->query->paginate(30);
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

        $this->dispatchBrowserEvent('toast', ['message' => 'Trashed Cleared', 'type' => 'success']);
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