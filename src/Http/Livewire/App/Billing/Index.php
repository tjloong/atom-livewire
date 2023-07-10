<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['refresh' => '$refresh'];

    // get subscriptions property
    public function getSubscriptionsProperty(): mixed
    {
        return model('plan_subscription')
            ->where('user_id', user('id'))
            ->status(['active', 'future'])
            ->orderBy('start_at')
            ->get();
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.billing');
    }
}