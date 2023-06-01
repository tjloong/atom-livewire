<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Jiannius\Atom\Jobs\PlanSubscriptionProvision;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Order extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $price;
    public $subscription;

    public $inputs = [
        'enable_auto_billing' => false,
        'agree_privacy' => false,
    ];

    protected $listeners = ['open'];

    protected function validation(): array
    {
        return [
            'subscription.currency' => ['nullable'],
            'subscription.amount' => ['nullable'],
            'subscription.discounted_amount' => ['nullable'],
            'subscription.extension' => ['nullable'],
            'subscription.start_at' => ['nullable'],
            'subscription.end_at' => ['nullable'],
            'subscription.data' => ['nullable'],
            'subscription.is_trial' => ['nullable'],
            'subscription.user_id' => ['required' => 'Subscription user is required.'],
            'subscription.price_id' => ['required' => 'Subscription price is required.'],

            'inputs.agree_privacy' => ['accepted' => 'Please agree to the privacy agreement.'],
            'inputs.enable_auto_billing' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Open
     */
    public function open($code): void
    {
        if ($this->price = model('plan_price')->where('code', $code)->first()) {
            $this->subscription = model('plan_subscription')->fill([
                'user_id' => user('id'),
                'price_id' => $this->price->id,
            ]);
    
            $this->subscription->setValidity();

            if ($this->subscription->getRelatives()->enabledAutoRenew()->count()) {
                $this->addError('subscription', 'There are subscriptions with auto renew enabled. Please cancel the auto renewal and try again.');
                $this->clear();
            }
            
            $this->dispatchBrowserEvent('order-open');
        }
    }

    /**
     * Clear
     */
    public function clear(): void
    {
        $this->price = null;
        $this->subscription = null;
        $this->dispatchBrowserEvent('order-close');
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $payment = $this->persist();

        if ($payment->amount) return $this->checkoutToStripe($payment);
        else return $this->provision($payment);
    }

    /**
     * Persist
     */
    public function persist(): mixed
    {
        $payment = model('plan_payment')->create([
            'currency' => $this->subscription->currency,
            'amount' => $this->subscription->grand_total,
            'description' => $this->subscription->price->description,
            'status' => 'draft',
            'data' => ['agree_privacy' => data_get($this->inputs, 'agree_privacy')],
            'user_id' => user('id'),
        ]);

        $this->subscription->fill(['payment_id' => $payment->id])->save();

        return $payment->fresh();
    }

    /**
     * Provision directly when no payment amount
     */
    public function provision($payment): mixed
    {
        PlanSubscriptionProvision::dispatchSync([
            'webhook' => true,
            'metadata' => [
                'payment_id' => $payment->id,
                'status' => 'success',
            ],
        ]);

        return redirect(user()->home())->with('plan-payment', 'success');
    }

    /**
     * Checkout to Stripe
     */
    public function checkoutToStripe($payment): mixed
    {
        $metadata = [
            'job' => 'PlanSubscriptionProvision',
            'payment_id' => $payment->id,
        ];

        $isAutoBilling = $this->price->is_recurring && data_get($this->inputs, 'enable_auto_billing');

        $customerId = data_get(
            model('plan_payment')
                ->whereHas('subscription', fn($q) => $q->where('user_id', user('id')))
                ->where('mode', 'stripe')
                ->whereNotNull('data->metadata->stripe_customer_id')
                ->latest()
                ->first(), 
            'data.metadata.stripe_customer_id'
        );
        
        $params = array_filter([
            'customer' => $customerId,
            'customer_email' => $customerId ? null : user('email'),
            'mode' => $isAutoBilling ? 'subscription' : 'payment',
            'metadata' => $metadata,
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => array_filter([
                        'currency' => $payment->currency,
                        'product_data' => ['name' => $this->price->description],
                        'unit_amount' => currency($payment->amount),
                        'recurring' => $isAutoBilling ? $this->price->valid : null,
                    ]),
                ],
            ],
            'subscription_data' => $isAutoBilling ? ['metadata' => $metadata] : null,
            'success_url' => route('__stripe.success', $metadata),
            'cancel_url' => route('__stripe.cancel', $metadata),
        ]);

        $payment->fill([
            'mode' => 'stripe',
            'data' => array_merge((array)$payment->data, [
                'pay_request' => $params,
            ]),
        ])->save();

        return stripe()->checkout($params);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.billing.order');
    }
}