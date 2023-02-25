<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Subscription;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort = 'start_at,desc';

    public $filters = [
        'search' => null,
        'status' => null,
        'plan' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
            'status' => null,
            'plan' => null,
        ]],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return tier('root') ? 'Plan Subscriptions' : 'Billing Management';
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('plan_subscription')
            ->readable()
            ->when(tier('root'), fn($q) => $q->filter($this->filters));
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Sign-Up',
                'label' => $query->user->name,
                'small' => $query->user->email,
                'href' => route('app.plan.subscription.update', [$query->id]),
            ],

            [
                'column_name' => 'Plan',
                'label' => $query->price->plan->name,
                'small' => $query->price->name,
                'href' => route('app.plan.subscription.update', [$query->id]),
            ],

            [
                'class' => 'text-right',
                'status' => array_merge(
                    $query->is_trial ? ['yellow' => 'trial'] : [],
                    [$query->status],
                ),
            ],

            [
                'column_name' => 'Start Date',
                'column_sort' => 'start_at',
                'column_class' => 'text-right',
                'class' => 'text-right',
                'date' => $query->start_at,
            ],

            [
                'column_name' => 'Expire Date',
                'column_sort' => 'expired_at',
                'date' => $query->expired_at,
            ],
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.subscription.listing');
    }
}