<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Subscription;

use Jiannius\Atom\Jobs\PlanPaymentProvision;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Create extends Component
{
    use WithForm;

    public $plan;
    public $price;
    public $items;
    public $order;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'order.data.agree_tnc' => ['accepted' => 'Please accept terms & conditions and privacy policy.'],
            'order.data.enable_auto_billing' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->plan = model('plan')->where('slug', request()->query('plan'))->status('active')->firstOrFail();
        $this->price = $this->plan->prices()->findOrFail(request()->query('price'));

        $this->order = ['data' => [
            'agree_tnc' => false, 
            'enable_auto_billing' => false,
        ]];

        $this->addItem();

        breadcrumbs()->push('Order Summary');
    }

    /**
     * Get total property
     */
    public function getTotalProperty(): array
    {
        $currency = data_get($this->items->first(), 'currency');
        $amount = $this->items->sum('grand_total');

        return compact('currency', 'amount');
    }

    /**
     * Add order item
     */
    public function addItem(): void
    {
        if (!$this->items) $this->items = collect();

        $trial = $this->plan->trial && !user()->hasPlanPrice($this->price->id) ? $this->plan->trial : false;
        $discount = $trial ? $this->price->amount : $this->price->discount;
        $item = [
            'currency' => $this->price->currency,
            'amount' => $this->price->amount,
            'discounted_amount' => $discount,
            'grand_total' => $this->price->amount - ($discount ?? 0),
            'plan_price_id' => $this->price->id,
            'data' => [
                'trial' => $trial,
                'recurring' => $this->price->is_recurring
                    ? ['interval' => 'month', 'count' => $this->price->expired_after]
                    : false,
            ],
        ];

        // item name
        $str = [$this->plan->payment_description ?? $this->plan->name];

        if ($trial) {
            $str[] = '('.__(':total days trial', ['total' => $trial]).')';
        }
        else if ($recurring = data_get($item, 'data.recurring')) {
            $count = data_get($recurring, 'count');
            $interval = data_get($recurring, 'interval');
            $str[] = $count.' '.str()->plural($interval, $count);
        }

        $item['name'] = implode(' ', $str);

        $this->items->push($item);
    }

    /**
     * Submit
     */
    public function submit($provider = null): mixed
    {
        $this->validateForm();

        $order = model('plan_order')->create(array_merge($this->order, [
            'currency' => data_get($this->total, 'currency'),
            'amount' => data_get($this->total, 'amount'),
            'user_id' => user('id'),
        ]));

        foreach ($this->items as $item) {
            $order->items()->create($item);
        }

        $payment = $order->payments()->create([
            'provider' => $provider ?? 'manual',
            'currency' => $order->currency,
            'amount' => $order->amount,
            'status' => $order->amount > 0 ? 'draft' : 'success',
        ]);

        // payment gateway
        if ($payment->amount > 0 && $provider) {
            $data = ['pay_request' => [
                'job' => 'PlanPaymentProvision',
                'customer' => array_merge(
                    ['email' => user('email')],
                    $provider === 'stripe'
                        ? ['stripe_customer_id' => $this->getStripeCustomerId()]
                        : []
                ),
                'payment_id' => $payment->id,
                'payment_description' => 'Plan Order #'.$order->number,
                'currency' => $payment->currency,
                'amount' => currency($payment->amount),
                'items' => $order->items->map(fn($item) => [
                    'name' => $item->name,
                    'amount' => currency($item->grand_total),
                    'currency' => $item->currency,
                    'qty' => 1,
                    'recurring' => data_get($order->data, 'enable_auto_billing')
                        ? data_get($item->data, 'recurring')
                        : false,
                ])->all(),
            ]];

            $payment->fill(compact('data'))->save();

            return redirect()->route('__'.$provider.'.checkout')->with($data);
        }
        // no payment amount, provision straight away
        else {
            PlanPaymentProvision::dispatchSync([
                'webhook' => true,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'status' => 'success',
                ],
            ]);
        }

        return redirect(user()->home());
    }

    /**
     * Get stripe customer id
     */
    public function getStripeCustomerId(): string
    {
        $history = model('plan_payment')
            ->whereHas('order', fn($q) => $q->where('user_id', user('id')))
            ->where('provider', 'stripe')
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
        return atom_view('app.plan.subscription.create');
    }
}