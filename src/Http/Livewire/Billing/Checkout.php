<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Checkout extends Component
{
    public $cart;
    public $accountOrder;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'accountOrder.agree_tnc' => 'accepted',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'accountOrder.agree_tnc.accepted' => __('Please accept terms & conditions and privacy policy.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (request()->query('plan') && request()->query('price')) {
            $this->accountOrder = model('account_order');
            $this->addToCart(request()->query('plan'), request()->query('price'));
        }
        else return redirect()->route('billing');
    }

    /**
     * Get total property
     */
    public function getTotalProperty()
    {
        $currency = $this->cart->first()['currency'];
        $amount = $this->cart->sum('grand_total');

        return compact('currency', 'amount');
    }

    /**
     * Add to cart
     */
    public function addToCart($planId, $priceId)
    {
        if (!$this->cart) $this->cart = collect([]);

        $plan = model('plan')->where('slug', $planId)->where('is_active', true)->firstOrFail();
        $price = $plan->prices()->findOrFail($priceId);
        $trial = $plan->trial && !auth()->user()->account->hasPlanPrice($price->id) ? $plan->trial : false;
        $discount = $trial ? $price->amount : $price->discount;
        
        $this->cart->push([
            'name' => $plan->payment_description,
            'currency' => $price->currency,
            'amount' => $price->amount,
            'discounted_amount' => $discount,
            'grand_total' => $price->amount - ($discount ?? 0),
            'trial' => $trial,
            'recurring' => $price->recurring,
            'plan_price_id' => $price->id,
        ]);
    }

    /**
     * Submit
     */
    public function submit($data = null)
    {
        $this->resetValidation();
        $this->validate();

        $this->accountOrder->currency = $this->total['currency'];
        $this->accountOrder->amount = $this->total['amount'];
        $this->accountOrder->account_id = auth()->user()->account_id;
        $this->accountOrder->save();

        foreach ($this->cart as $cartItem) {
            $orderItem = collect($cartItem)->only(['currency', 'amount', 'discounted_amount', 'grand_total', 'plan_price_id'])->all();
            $orderItem['name'] = $cartItem['name'].($cartItem['trial'] 
                ? (' ('.$cartItem['trial'].' days trial)') 
                : '');

            $this->accountOrder->accountOrderItems()->create($orderItem);
        }

        $payment = $this->accountOrder->accountPayments()->create([
            'currency' => $this->accountOrder->currency,
            'amount' => $this->accountOrder->amount,
            'status' => $this->accountOrder->amount > 0 ? 'draft' : 'success',
            'account_id' => auth()->user()->account_id,
        ]);

        // payment gateway data
        if ($payment->amount > 0 && $data) {
            $request = array_merge($data, [
                'job' => 'Billing',
                'email' => auth()->user()->account->email,
                'payment_id' => $payment->id,
                'payment_description' => 'Payment for order #'.$this->accountOrder->number,
                'currency' => $payment->currency,
                'amount' => currency($payment->amount),
                'items' => $this->accountOrder->accountOrderItems->map(fn($item) => [
                    'name' => $item->name,
                    'amount' => currency($item->grand_total),
                    'currency' => $item->currency,
                    'qty' => 1,
                    'stripe_price_id' => $item->planPrice->stripe_price_id,
                ]),
            ]);

            $payment->data = ['pay_request' => $request];
            $payment->save();

            return $request;
        }
        // no payment amount, provision straight away
        else $payment->provision();
        
        if (auth()->user()->account->status === 'onboarded') return redirect()->route(app_route());
        else return redirect()->route('onboarding');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::billing.checkout')->layout('layouts.billing');
    }
}