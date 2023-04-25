<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Illuminate\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Receipt extends Component
{
    use WithTable;

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('plan_payment')
            ->whereHas('subscription', fn($q) => $q->where('user_id', user('id')))
            ->latest();
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Date',
                'sort' => 'created_at',
                'date' => $query->created_at,
            ],
            
            [
                'name' => 'Description',
                'label' => str($query->description)->limit(50),
            ],

            [
                'name' => 'Status',
                'status' => array_filter([
                    $query->status,
                    $query->is_auto_billing ? 'auto' : null,
                ]),
            ],
            
            [
                'name' => 'Amount',
                'sort' => 'amount',
                'amount' => $query->amount,
                'currency' => $query->currency,
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.billing.receipt');
    }
}