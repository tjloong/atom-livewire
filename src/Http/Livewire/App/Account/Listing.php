<?php

namespace Jiannius\Atom\Http\Livewire\App\Account;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $title = 'Accounts';
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters' => ['except' => ['search' => '']],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home($this->title);
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return model('user')
            ->whereHas('account', fn($q) => $q->filter($this->filters))
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
        return view('atom::app.account.listing', [
            'users' => $this->users->paginate(30),
        ]);
    }

    /**
     * Export
     */
    public function export()
    {
        $filename = 'accounts-' . rand(1000, 9999) . '.xlsx';
        $users = $this->users->get();

        return export_to_excel($filename, $users, fn($user) => [
            'Date' => $user->created_at->toDatetimeString(),
            'Name' => $user->name,
            'Phone' => $user->account->phone,
            'Email' => $user->email ?? $user->account->email,
            'Agreed to T&C' => $user->account->agree_tnc ? 'Yes' : 'No',
            'Agreed to Marketing' => $user->account->agree_marketing ? 'Yes' : 'No',
            'Status' => $user->account->status,
            'Blocked Date' => optional($user->account->blocked_at)->toDatetimeString(),
        ]);
    }
}