<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Subscription;

use Illuminate\Contracts\Database\Eloquent\Builder;
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

    /**
     * Mount
     */
    public function mount(): void
    {
        breadcrumbs()->home($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return tier('root') ? 'Plan Subscriptions' : 'Billing Management';
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('plan_subscription')
            ->readable()
            ->when(tier('root'), fn($q) => $q->filter($this->filters));
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Sign-Up',
                'label' => $query->user->name,
                'small' => $query->user->email,
                'href' => route('app.plan.subscription.update', [$query->id]),
            ],

            [
                'name' => 'Plan',
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
                'name' => 'Start Date',
                'sort' => 'start_at',
                'class' => 'text-right',
                'date' => $query->start_at,
            ],

            [
                'name' => 'Expire Date',
                'sort' => 'expired_at',
                'date' => $query->expired_at,
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.subscription.listing');
    }
}