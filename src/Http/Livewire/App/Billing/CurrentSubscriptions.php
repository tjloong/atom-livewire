<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class CurrentSubscriptions extends Component
{
    public $account;

    /**
     * Get account subscriptions property
     */
    public function getAccountSubscriptionsProperty()
    {
        return model('account_subscription')
            ->where('account_id', $this->account->id)
            ->status(['active', 'pending'])
            ->latest()
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.billing.current-subscriptions');
    }
}