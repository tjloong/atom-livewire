<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Index extends Component
{
    public $user;

    /**
     * Mount
     */
    public function mount()
    {
        $this->user = auth()->user();
    }

    /**
     * Get subscriptions property
     */
    public function getSubscriptionsProperty()
    {
        if (enabled_module('signups')) $entity = $this->user->signup;
        else if (enabled_module('tenants')) $entity = $this->user->tenant;

        $subscriptions = optional($entity)->subscriptions();

        return $subscriptions ? $subscriptions->status('active')->get() : [];
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        return model('plan')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.index', [
            'plans' => $this->plans,
            'subscriptions' => $this->subscriptions,
        ])->layout('layouts.billing');
    }
}