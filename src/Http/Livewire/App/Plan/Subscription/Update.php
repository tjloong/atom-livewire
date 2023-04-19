<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Subscription;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;
    use WithForm;
    use WithPopupNotify;

    public $subscription;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'subscription.start_at' => ['required' => 'Start date is required.'],
            'subscription.expired_at' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount($subscriptionId): void
    {
        $this->authorize('tier:root');

        $this->subscription = model('plan_subscription')->readable()->findOrFail($subscriptionId);

        breadcrumbs()->push($this->subscription->user->name);
    }

    /**
     * Get payment property
     */
    public function getPaymentProperty(): mixed
    {
        return $this->subscription->item->order->payments()->status('success')->latest()->first();
    }

    /**
     * Mask
     */
    public function mask(): mixed
    {
        session(['mask' => user()]);

        auth()->logout();

        $subscriber = $this->subscription->user;
        auth()->login($subscriber);

        return redirect($subscriber->home());
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->subscription->save();

        $this->popup('Subscription Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.subscription.update');
    }
}