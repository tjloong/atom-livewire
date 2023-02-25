<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Subscription;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Update extends Component
{
    use AuthorizesRequests;
    use WithPopupNotify;

    public $subscription;

    protected $rules = [
        'subscription.start_at' => 'required',
        'subscription.expired_at' => 'nullable',
    ];

    protected $messages = [
        'subscription.start_at.required' => 'Start date is required.',
    ];

    /**
     * Mount
     */
    public function mount($subscriptionId)
    {
        $this->authorize('tier:root');

        $this->subscription = model('plan_subscription')->readable()->findOrFail($subscriptionId);

        breadcrumbs()->push($this->subscription->user->name);
    }

    /**
     * Get payment property
     */
    public function getPaymentProperty()
    {
        return $this->subscription->item->order->payments()->status('success')->latest()->first();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->subscription->save();

        $this->popup('Subscription Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.subscription.update');
    }
}