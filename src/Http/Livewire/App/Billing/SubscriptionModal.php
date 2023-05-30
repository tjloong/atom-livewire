<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class SubscriptionModal extends Component
{
    public $queue;
    public $subscription;

    protected $listeners = ['open'];

    /**
     * Open
     */
    public function open($id)
    {
        $this->subscription = model('plan_subscription')->find($id);
        
        $this->dispatchBrowserEvent('subscription-modal-open');
    }

    /**
     * Cancel auto renew
     */
    public function cancel($bool = null)
    {
        $stripeSubscriptionId = data_get($this->subscription->data, 'stripe_subscription_id');

        if ($bool === true) {
            // cancel stripe subscriptions
            if ($stripeSubscriptionId) {
                stripe()->cancelSubscription($stripeSubscriptionId);

                $this->queue->each(fn($item) => $item->fill([
                    'data' => array_merge((array)$item->data, ['stripe_subscription_id' => null]),
                ])->save());
            }

            // cancel revenue monster subscriptions

            $this->queue = null;
            $this->emit('refresh');
            $this->dispatchBrowserEvent('subscription-modal-close');
        }
        else if ($bool === false) {
            $this->queue = null;
        }
        else {
            if ($stripeSubscriptionId) $this->queue = model('plan_subscription')->where('data->stripe_subscription_id', $stripeSubscriptionId)->get();

            // revenue monster
        }
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.billing.subscription-modal');
    }
}