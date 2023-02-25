<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Price;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $plan;

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return $this->plan->prices()
            ->withCount('users')
            ->orderBy('currency')
            ->orderBy('expired_after')
            ->orderBy('amount');
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Price',
                'label' => implode(' / ', [
                    currency($query->amount, $query->currency),
                    $query->recurring,
                ]),
                'href' => route('app.plan.price.update', [
                    'planId' => $this->plan->id,
                    'priceId' => $query->id,
                ]),
            ],

            [
                'status' => collect([
                    'recurring' => $query->is_recurring,
                    'default' => $query->is_default,
                ])->filter()->keys()->values()->all(),
            ],

            [
                'column_name' => 'Available In',
                'label' => $query->country ?? 'All Countries',
            ],

            [
                'column_name' => 'Subscribers',
                'count' => $query->users_count,
                'uom' => 'subscriber',
            ],
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.price.listing');
    }
}