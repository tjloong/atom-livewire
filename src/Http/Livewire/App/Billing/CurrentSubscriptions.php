<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class CurrentSubscriptions extends Component
{
    public $account;

    /**
     * Get subscriptions property
     */
    public function getSubscriptionsProperty()
    {
        return model('account_subscription')
            ->where('account_id', $this->account->id)
            ->status(['active', 'pending'])
            ->latest()
            ->get();
    }

    /**
     * Open cancel auto billing modal
     */
    public function openCancelAutoBillingModal($subscriptionId)
    {
        $this->emitTo(lw('app.billing.cancel-auto-billing-modal'), 'open', $subscriptionId);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.billing.current-subscriptions');
    }
}