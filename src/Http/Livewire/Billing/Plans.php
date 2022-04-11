<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Plans extends Component
{
    public $plan;

    protected $queryString = ['plan'];

    /**
     * Mount
     */
    public function mount()
    {
        if ($this->subscriptions->count() && !$this->plan) return redirect()->route('billing');
    }

    /**
     * Get subscriptions property
     */
    public function getSubscriptionsProperty()
    {
        return auth()->user()->account->accountSubscriptions()
            ->status('active')
            ->get();
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        $isSubscribed = $this->subscriptions->count() > 0;

        if ($isSubscribed && $this->plan) {
            $plan = model('plan')->where('slug', $this->plan)->where('is_active', true)->firstOrFail();
            $plans = collect([$plan]);

            foreach ($plan->upgradables as $upgradable) {
                if (!$plans->search(fn($val) => $val->id === $upgradable->id)) $plans->push($upgradable);
            }

            foreach ($plan->downgradables as $downgradable) {
                if (!$plans->search(fn($val) => $val->id === $downgradable->id)) $plans->push($downgradable);
            }

            return $plans;
        }
        else if (!$isSubscribed) {
            return model('plan')
                ->where('is_active', true)
                ->orderBy('id')
                ->get();
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.plans')->layout('layouts.billing');
    }
}