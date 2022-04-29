<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Index extends Component
{
    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->plans->count()) return redirect()->route('billing.plans');
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        $query = auth()->user()->account->accountSubscriptions()->status(['active', 'pending']);

        return model('plan')->whereHas('planPrices', fn($q) => 
            $q->whereIn(
                'plan_prices.id', 
                (clone $query)->select('plan_price_id')->get()->pluck('plan_price_id')->unique()->all()
            )
        )->get()->map(function($plan) use ($query) {
            $subscriptions = (clone $query)
                ->whereHas('planPrice', fn($q) => $q->where('plan_id', $plan->id))
                ->orderBy('created_at')
                ->get();

            $plan->is_trial = $subscriptions->count() === 1 && $subscriptions->first()->is_trial;
            $plan->expired_at = $subscriptions->where('expired_at', null)->count()
                ? null
                : $subscriptions->last()->expired_at;

            return $plan;
        });
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.index')->layout('layouts.billing');
    }
}