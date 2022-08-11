<?php

namespace Jiannius\Atom\Http\Livewire\App\Account;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

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
     * Get title propert
     */
    public function getTitleProperty()
    {
        return 'Accounts';
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('account')
            ->where('type', 'signup')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Get accounts property
     */
    public function getAccountsProperty()
    {
        return $this->query->paginate(100);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Export
     */
    public function export()
    {
        $filename = 'accounts-' . rand(1000, 9999) . '.xlsx';
        $accounts = $this->query->get();

        return export_to_excel($filename, $accounts, fn($account) => [
            'Date' => $account->created_at->toDatetimeString(),
            'Name' => $account->name,
            'Email' => $account->email,
            'Agreed to T&C' => $account->agree_tnc ? 'Yes' : 'No',
            'Agreed to Marketing' => $account->agree_marketing ? 'Yes' : 'No',
            'Status' => $account->status,
            'Blocked Date' => optional($account->blocked_at)->toDatetimeString(),
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.listing');
    }
}