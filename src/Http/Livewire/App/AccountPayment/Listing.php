<?php

namespace Jiannius\Atom\Http\Livewire\App\AccountPayment;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $account;
    public $fullpage;
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
        if ($this->fullpage = current_route('app.account-payment.listing')) {
            breadcrumbs()->home($this->title);
        }
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
        return model('account_payment')
            ->when(
                auth()->user()->isAccountType('root'), 
                fn($q) => $q->when($this->account, fn($q) => $q->where('account_id', $this->account->id)),
                fn($q) => $q->where('account_id', auth()->user()->account_id)->where('status', 'success')
            )
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
        return atom_view('app.account-payment.listing');
    }
}