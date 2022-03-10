<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Subscriptions extends Component
{
    public $subscriptions;

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.subscriptions')->layout('layouts.billing');
    }
}