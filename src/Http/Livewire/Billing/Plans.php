<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Plans extends Component
{
    public $plans;
    public $subscriptions;

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.plans')->layout('layouts.billing');
    }
}