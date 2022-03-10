<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Checkout extends Component
{
    public $plan;
    public $price;

    /**
     * Mount
     */
    public function mount()
    {
        if (!request()->query('plan') && !request()->query('price')) return redirect()->route('billing');

        $this->plan = model('plan')->where('slug', request()->query('plan'))->where('is_active', true)->firstOrFail();
        $this->price = $this->plan->prices()->findOrFail(request()->query('price'));
    }

    /**
     * Get total property
     */
    public function getTotalProperty()
    {
        return [
            'amount' => $this->price->amount,
            'currency' => $this->price->currency,
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.checkout', [
            'total' => $this->total,
        ])->layout('layouts.billing');
    }
}