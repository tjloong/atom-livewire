<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Jiannius\Atom\Jobs\PlanPaymentProvision;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Checkout extends Component
{
    use WithForm;

    public $payment;
    public $subscription;

    public $inputs = [
        'enable_auto_billing' => false,
        'agree_privacy' => false,
    ];

    protected function validation(): array
    {
        return [
            'payment.currency' => ['nullable'],
            'payment.amount' => ['nullable'],
            'payment.price_id' => ['required' => 'Price is required.'],
            'payment.user_id' => ['required' => 'User is required.'],

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
        return model('plan')->subscribeable()->with('prices')->get();
    }

    /**
     * Select price
     */
    public function select($code): void
    {
        $price = model('plan_price')->where('code', $code)->first();

        $subscription = model('plan_subscription')->fill([
            'user_id' => user('id'),
            'price_id' => $price->id,
        ]);

        $subscription->setValidity();

        $this->payment = model('plan_payment')->fill([
            'currency' => $price->plan->currency,
            'amount' => $subscription->is_trial ? 0 : ($price->amount ?? 0),
            'price_id' => $price->id,
            'user_id' => user('id'),
        ]);

        $this->subscription = $subscription->toArray();
    }

    /**
     * Clear
     */
    public function clear(): void
    {
        $this->subscription = null;
        $this->payment = null;
    }

    /**
     * Submit
     */
    public function submit($mode = null)
    {
        $this->validateForm();

        $this->payment->fill([
            'mode' => $mode,
            'description' => $this->payment->price->description,
            'status' => 'draft',
            'data' => ['agree_privacy' => data_get($this->inputs, 'agree_privacy')],
        ])->save();

        // no payment amount, provision straight away
        if (!$this->payment->amount) {
            PlanPaymentProvision::dispatchSync([
                'webhook' => true,
                'metadata' => [
                    'payment_id' => $this->payment->id,
                    'status' => 'success',
                ],
            ]);

            return redirect()->route('app.settings', ['billing']);
        }
        // payment gateway
        else {
            $req = [
                'job' => 'PlanPaymentProvision',
                'customer' => array_merge(
                    ['email' => user('email')],
                    $mode === 'stripe'
                        ? ['stripe_customer_id' => $this->getStripeCustomerId()]
                        : []
                ),
                'payment_id' => $this->payment->id,
                'payment_description' => 'Plan Payment #'.$this->payment->number,
                'currency' => $this->payment->currency,
                'amount' => currency($this->payment->amount),
                'items' => [
                    [
                        'name' => $this->payment->price->description,
                        'amount' => currency($this->payment->amount),
                        'currency' => $this->payment->currency,
                        'qty' => 1,
                        'recurring' => $this->payment->price->is_recurring && data_get($this->inputs, 'enable_auto_billing')
                            ? $this->payment->price->valid
                            : false,
                    ],
                ],
            ];

            $this->payment->fill([
                'data' => array_merge((array)$this->payment->data, [
                    'pay_request' => $req,
                ]),
            ])->save();

            return redirect()->route('__'.$mode.'.checkout')->with([
                'pay_request' => $req,
            ]);
        }
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