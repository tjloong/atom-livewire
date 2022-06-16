<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Index extends Component
{
    public $account;

    /**
     * Mount
     */
    public function mount()
    {
        if(!$this->account) $this->account = auth()->user()->account;
        if (!$this->plans->count()) return redirect()->route('billing.plans');
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        $query = $this->account->accountSubscriptions()->status(['active', 'pending']);

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
        $view = view('atom::billing.index');
        
        return current_route('app.billing*') ? $view : $view->layout('layouts.billing');
    }
}