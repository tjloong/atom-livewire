<?php

namespace Jiannius\Atom\Http\Livewire\Billing\AccountPayment;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $account;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters' => ['except' => ['search' => '']],
        'sortBy' => ['except' => 'created_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        if ($this->isFullpage) breadcrumbs()->home($this->title);
    }

    /**
     * Get is fullpage property
     */
    public function getIsFullpageProperty()
    {
        return current_route('app.account-payment.listing');
    }

    /**
     * Get title propert
     */
    public function getTitleProperty()
    {
        return 'Account Payments';
    }

    /**
     * Get account payments property
     */
    public function getAccountPaymentsProperty()
    {
        return $this->account->accountPayments()
            ->when(!auth()->user()->isAccountType('root'), fn($q) => $q->where('status', 'success'))
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
        return view('atom::billing.account-payment.listing');
    }
}