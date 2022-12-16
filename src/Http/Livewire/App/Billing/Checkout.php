<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class Checkout extends Component
{
    public $order;
    public $orderItems;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'order.data.agree_tnc' => 'accepted',
            'order.data.enable_auto_billing' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'order.data.agree_tnc.accepted' => __('Please accept terms & conditions and privacy policy.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (request()->query('plan') && request()->query('price')) {
            $this->order = ['data' => [
                'agree_tnc' => false, 
                'enable_auto_billing' => false,
            ]];

            $this->addOrderItem(request()->query('plan'), request()->query('price'));

            breadcrumbs()->push('Order Summary');
        }
        else return redirect()->route('app.billing.view');
    }

    /**
     * Get total property
     */
    public function getTotalProperty()
    {
        $currency = data_get($this->orderItems->first(), 'currency');
        $amount = $this->orderItems->sum('grand_total');

        return compact('currency', 'amount');
    }

    /**
     * Add order item
     */
    public function addOrderItem($planId, $priceId)
    {
        if (!$this->orderItems) $this->orderItems = collect();

        $plan = model('plan')->where('slug', $planId)->status('active')->firstOrFail();
        $price = $plan->prices()->findOrFail($priceId);
        $trial = $plan->trial && !auth()->user()->account->hasPlanPrice($price->id) ? $plan->trial : false;
        $discount = $trial ? $price->amount : $price->discount;
        $item = [
            'currency' => $price->currency,
            'amount' => $price->amount,
            'discounted_amount' => $discount,
            'grand_total' => $price->amount - ($discount ?? 0),
            'plan_price_id' => $price->id,
            'data' => [
                'trial' => $trial,
                'recurring' => $price->is_recurring
                    ? ['interval' => 'month', 'count' => $price->expired_after]
                    : false,
            ],
        ];

        // item name
        $str = [$plan->payment_description ?? $plan->name];

        if ($trial) {
            $str[] = '('.__(':total days trial', ['total' => $trial]).')';
        }
        else if ($recurring = data_get($item, 'data.recurring')) {
            $count = data_get($recurring, 'count');
            $interval = data_get($recurring, 'interval');
            $str[] = $count.' '.str()->plural($interval, $count);
        }

        $item['name'] = implode(' ', $str);

        $this->orderItems->push($item);
    }

    /**
     * Submit
     */
    public function submit($provider = null)
    {
        $this->resetValidation();
        $this->validate();

        $order = model('account_order')->create(array_merge($this->order, [
            'currency' => data_get($this->total, 'currency'),
            'amount' => data_get($this->total, 'amount'),
            'account_id' => auth()->user()->account_id,
        ]));

        foreach ($this->orderItems as $orderItem) {
            $order->items()->create($orderItem);
        }

        $payment = $order->payments()->create([
            'provider' => $provider ?? 'manual',
            'currency' => $order->currency,
            'amount' => $order->amount,
            'status' => $order->amount > 0 ? 'draft' : 'success',
            'account_id' => auth()->user()->account_id,
        ]);

        // payment gateway
        if ($payment->amount > 0 && $provider) {
            $data = ['pay_request' => [
                'job' => 'AccountPaymentProvision',
                'customer' => array_merge(
                    ['email' => data_get(auth()->user()->account, 'email')],
                    $provider === 'stripe'
                        ? ['stripe_customer_id' => $this->getStripeCustomerId()]
                        : []
                ),
                'payment_id' => $payment->id,
                'payment_description' => 'Account Order #'.$order->number,
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
        else $payment->provision();

        return auth()->user()->account->status === 'new'
            ? redirect()->route('app.onboarding.home')
            : redirect(auth()->user()->home());
    }

    /**
     * Get stripe customer id
     */
    public function getStripeCustomerId()
    {
        $previousPayment = model('account_payment')
            ->where('account_id', auth()->user()->account_id)
            ->where('provider', 'stripe')
            ->whereNotNull('data->metadata->stripe_customer_id')
            ->latest()
            ->first();

        return data_get($previousPayment, 'data.metadata.stripe_customer_id');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.billing.checkout');
    }
}