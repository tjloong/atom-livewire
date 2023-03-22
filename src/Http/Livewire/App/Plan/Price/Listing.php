<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Price;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $plan;

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
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
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Price',
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
                'name' => 'Available In',
                'label' => $query->country ?? 'All Countries',
            ],

            [
                'name' => 'Subscribers',
                'count' => $query->users_count,
                'uom' => 'subscriber',
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.price.listing');
    }
}