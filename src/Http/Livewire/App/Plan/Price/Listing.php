<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Price;

use Livewire\Component;

class Listing extends Component
{
    public $plan;

    /**
     * Get prices property
     */
    public function getPricesProperty()
    {
        return $this->plan->prices()
            ->orderBy('currency')
            ->orderBy('expired_after')
            ->orderBy('amount')
            ->get()
            ->transform(fn($price) => [
                [
                    'column_name' => 'Price',
                    'label' => implode(' / ', [
                        currency($price->amount, $price->currency),
                        $price->recurring,
                    ]),
                    'href' => route('app.plan.price.update', [
                        'planId' => $this->plan->id,
                        'priceId' => $price->id,
                    ]),
                ],

                [
                    'status' => collect([
                        'recurring' => $price->is_recurring,
                        'default' => $price->is_default,
                    ])->filter()->keys()->values()->all(),
                ],

                [
                    'column_name' => 'Available In',
                    'label' => $price->country
                        ? data_get(metadata()->countries($price->country), 'name')
                        : 'All countries',
                ],

                [
                    'column_name' => 'Subscribers',
                    'count' => $price->accounts->count(),
                    'uom' => 'subscriber',
                ],
            ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.price.listing');
    }
}