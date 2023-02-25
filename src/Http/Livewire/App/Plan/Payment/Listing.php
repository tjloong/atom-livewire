<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Payment;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

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
        if ($this->fullpage = current_route('app.plan.payment.listing')) {
            breadcrumbs()->home($this->title);
        }
    }

    /**
     * Get title propert
     */
    public function getTitleProperty()
    {
        return $this->fullpage ? 'Plan Payments' : 'Payment History';
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('plan_payment')
            ->readable()
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Date',
                'column_sort' => 'created_at',
                'datetime' => $query->created_at,
            ],
            
            [
                'column_name' => 'Receipt',
                'label' => $query->number,
                'href' => route('app.plan.payment.update', [$query->id]),
            ],

            [
                'column_name' => 'Description',
                'label' => str($query->description)->limit(50),
                'small' => tier('root') ? $query->order->user->name : null,
            ],

            [
                'column_name' => 'Status',
                'status' => array_filter([
                    $query->status,
                    $query->is_auto_billing ? 'auto' : null,
                ]),
            ],
            
            [
                'column_name' => 'Amount',
                'column_sort' => 'amount',
                'amount' => $query->amount,
                'currency' => $query->currency,
            ],
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.payment.listing');
    }
}