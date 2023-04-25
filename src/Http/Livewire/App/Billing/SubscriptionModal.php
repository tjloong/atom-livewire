<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class SubscriptionModal extends Component
{
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
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.billing.subscription-modal');
    }
}