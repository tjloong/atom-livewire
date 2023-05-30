<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->homeIf(current_route('app.billing'), 'Subscription');
    }

    /**
     * Get subscriptions property
     */
    public function getSubscriptionsProperty(): mixed
    {
        return model('plan_subscription')
            ->where('user_id', user('id'))
            ->status(['active', 'future'])
            ->orderBy('start_at')
            ->get();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.billing');
    }
}