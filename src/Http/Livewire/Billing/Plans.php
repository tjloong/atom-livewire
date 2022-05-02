<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Plans extends Component
{
    public $plan;

    /**
     * Mount
     */
    public function mount()
    {
        $this->plan = request()->query('plan')
            ? model('plan')->where('slug', request()->query('plan'))->where('is_active', true)->first()
            : null;

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
        if ($this->subscriptions->count()) {
            return collect([$this->plan])
                ->concat($this->plan->upgradables)
                ->concat($this->plan->downgradables)
                ->unique('id');
        }
        else {
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