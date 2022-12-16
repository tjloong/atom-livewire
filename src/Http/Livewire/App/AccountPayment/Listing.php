<?php

namespace Jiannius\Atom\Http\Livewire\App\AccountPayment;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $account;
    public $fullpage;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => null];

    protected $queryString = [
        'sortBy' => ['except' => 'created_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
        'filters' => ['except' => [
            'search' => null,
        ]],
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
    public function getPaymentsProperty()
    {
        return model('account_payment')
            ->when(
                auth()->user()->isAccountType('root'), 
                fn($q) => $q->when($this->account, fn($q) => $q->where('account_id', $this->account->id)),
                fn($q) => $q->where('account_id', auth()->user()->account_id)->where('status', 'success')
            )
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows)
            ->through(fn($payment) => [
                [
                    'column_name' => 'Date',
                    'column_sort' => 'created_at',
                    'date' => $payment->created_at,
                ],
                [
                    'column_name' => 'Receipt Number',
                    'label' => $payment->number,
                    'href' => route('app.account-payment.update', [$payment->id]),
                    'small' => $payment->description,
                ],
                [
                    'column_name' => 'Status',
                    'status' => array_filter([
                        $payment->status,
                        $payment->is_auto_billing ? 'auto' : null,
                    ]),
                ],
                [
                    'column_name' => 'Amount',
                    'column_sort' => 'amount',
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                ],
            ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.account-payment.listing');
    }
}