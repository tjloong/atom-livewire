<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing\Payment;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $account;
    public $fullpage;
    public $sort = 'created_at,desc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
        ]],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        if ($this->fullpage = current_route('app.billing.payment.listing')) {
            breadcrumbs()->home($this->title);
        }
    }

    /**
     * Get title propert
     */
    public function getTitleProperty()
    {
        return 'Billing';
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
                    'datetime' => $payment->created_at,
                ],
                
                [
                    'column_name' => 'Receipt Number',
                    'label' => $payment->number,
                    'href' => route('app.billing.payment.update', [$payment->id]),
                    'small' => $payment->description,
                ],

                $this->fullpage ? [
                    'column_name' => 'Account',
                    'label' => $payment->account->name,
                    'href' => route('app.account.update', [$payment->account_id]),
                ] : null,

                $this->fullpage ? [
                    'column_name' => 'Method',
                    'column_sort' => 'provider',
                    'label' => str($payment->provider)->title(),
                ] : null,

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
        return atom_view('app.billing.payment.listing');
    }
}