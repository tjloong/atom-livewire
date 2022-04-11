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
        if (!$this->subscriptions->count()) return redirect()->route('billing.plans');
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
     * Render
     */
    public function render()
    {
        return view('atom::billing.index')->layout('layouts.billing');
    }
}