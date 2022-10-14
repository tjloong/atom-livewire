<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class CancelAutoBillingModal extends Component
{
    public $stripeSubscriptionId;
    public $accountSubscriptions;
    public $listeners = ['open'];

    /**
     * Open modal
     */
    public function open($id)
    {
        $this->stripeSubscriptionId = data_get(
            model('account_subscription')
                ->whereNotNull('data->stripe_subscription_id')
                ->find($id),
            'data.stripe_subscription_id'
        );

        $this->accountSubscriptions = model('account_subscription')
            ->where('data->stripe_subscription_id', $this->stripeSubscriptionId)
            ->get();

        $this->dispatchBrowserEvent('cancel-auto-billing-modal-open');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.billing.cancel-auto-billing-modal');
    }
}