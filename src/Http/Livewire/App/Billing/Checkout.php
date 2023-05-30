<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Jiannius\Atom\Jobs\PlanSubscriptionProvision;
use Jiannius\Atom\Models\PlanPayment;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Checkout extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $price;
    public $subscription;

    public $inputs = [
        'enable_auto_billing' => false,
        'agree_privacy' => false,
    ];

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
        breadcrumbs()->push($this->title);

        if ($code = request()->query('plan')) $this->select($code);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return user()->can('plan') ? 'Change Subscription' : 'Subscribe Plan';
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        return model('plan')
            ->subscribeable()
            ->with('prices')
            ->get()
            ->map(function ($plan) {
                $plan->subscription = model('plan_subscription')
                    ->status(['active', 'future'])
                    ->plan($plan->id)
                    ->where('user_id', user('id'))
                    ->latest('id')
                    ->first();
                return $plan;
            });
    }

    /**
     * Select price
     */
    public function select($code): mixed
    {
        if ($this->price = model('plan_price')->where('code', $code)->first()) {
            $this->subscription = model('plan_subscription')->fill([
                'user_id' => user('id'),
                'price_id' => $this->price->id,
            ]);
    
            $this->subscription->setValidity();

            if ($this->subscription->getRelatives()->enabledAutoRenew()->count()) {
                $this->clear();

                return $this->popup([
                    'title' => 'Unable To Subscribe Plan',
                    'message' => 'There are subscriptions with auto renew enabled. Please cancel the auto renewal and try again.',
                ], 'alert', 'error');
            }
        }
        
        return false;
    }

    /**
     * Clear
     */
    public function clear(): void
    {
        $this->price = null;
        $this->subscription = null;
    }

    /**
     * Submit
     */
    public function submit($mode = null)
    {
        $this->validateForm();

        $payment = $this->persist($mode);

        // no payment amount, provision straight away
        if (!$payment->amount) {
            PlanSubscriptionProvision::dispatchSync([
                'webhook' => true,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'status' => 'success',
                ],
            ]);

            return redirect(user()->home())->with('plan-payment', 'success');
        }
        // payment gateway
        else {
            $req = [
                'job' => 'PlanSubscriptionProvision',
                'customer' => array_merge(
                    ['email' => user('email')],
                    $mode === 'stripe'
                        ? ['stripe_customer_id' => $this->getStripeCustomerId()]
                        : []
                ),
                'payment_id' => $payment->id,
                'payment_description' => 'Plan Payment #'.$payment->number,
                'currency' => $payment->currency,
                'amount' => currency($payment->amount),
                'items' => [
                    [
                        'name' => $this->price->description,
                        'amount' => currency($payment->amount),
                        'currency' => $payment->currency,
                        'qty' => 1,
                        'recurring' => $this->price->is_recurring && data_get($this->inputs, 'enable_auto_billing')
                            ? $this->price->valid
                            : false,
                    ],
                ],
            ];

            $payment->fill([
                'data' => array_merge((array)$payment->data, [
                    'pay_request' => $req,
                ]),
            ])->save();

            return redirect()->route('__'.$mode.'.checkout')->with([
                'pay_request' => $req,
            ]);
        }
    }

    /**
     * Persist
     */
    public function persist($mode): PlanPayment
    {
        $payment = model('plan_payment')->create([
            'mode' => $mode,
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
     * Get stripe customer id
     */
    public function getStripeCustomerId(): mixed
    {
        $history = model('plan_payment')
            ->whereHas('subscription', fn($q) => $q->where('user_id', user('id')))
            ->where('mode', 'stripe')
            ->whereNotNull('data->metadata->stripe_customer_id')
            ->latest()
            ->first();

        return data_get($history, 'data.metadata.stripe_customer_id');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.billing.checkout');
    }
}