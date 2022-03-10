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
        //
    }

    /**
     * Get subscriptions property
     */
    public function getSubscriptionsProperty()
    {
        $account = auth()->user()->account;

        return $account
            ? $account->subscriptions()->status('active')->get()
            : collect([]);
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