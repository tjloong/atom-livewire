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
        $currency = data_get($this->cart->first(), 'currency');
        $amount = $this->cart->sum('grand_total');

        return compact('currency', 'amount');
    }

    /**
     * Add to cart
     */
    public function addToCart($planId, $planPriceId)
    {
        if (!$this->cart) $this->cart = collect([]);

        $plan = model('plan')->where('slug', $planId)->where('is_active', true)->firstOrFail();
        $planPrice = $plan->planPrices()->findOrFail($planPriceId);
        $trial = $plan->trial && !auth()->user()->account->hasPlanPrice($planPrice->id) ? $plan->trial : false;
        $discount = $trial ? $planPrice->amount : $planPrice->discount;
        
        $this->cart->push([
            'name' => $plan->payment_description,
            'currency' => $planPrice->currency,
            'amount' => $planPrice->amount,
            'discounted_amount' => $discount,
            'grand_total' => $planPrice->amount - ($discount ?? 0),
            'trial' => $trial,
            'recurring' => $planPrice->recurring,
            'plan_price_id' => $planPrice->id,
        ]);
    }

    /**
     * Submit
     */
    public function submit($data = null)
    {
        $this->resetValidation();
        $this->validate();

        $this->accountOrder->fill([
            'currency' => data_get($this->total, 'currency'),
            'amount' => data_get($this->total, 'amount'),
            'account_id' => auth()->user()->account_id,
        ])->save();

        foreach ($this->cart as $cartItem) {
            $orderItem = collect($cartItem)->only(['currency', 'amount', 'discounted_amount', 'grand_total', 'plan_price_id'])->all();
            $orderItem['name'] = $cartItem['name'].($cartItem['trial'] 
                ? (' ('.$cartItem['trial'].' days trial)') 
                : '');

            $this->accountOrder->accountOrderItems()->create($orderItem);
        }

        $accountPayment = $this->accountOrder->accountPayments()->create([
            'currency' => $this->accountOrder->currency,
            'amount' => $this->accountOrder->amount,
            'status' => $this->accountOrder->amount > 0 ? 'draft' : 'success',
            'account_id' => auth()->user()->account_id,
        ]);

        // payment gateway data
        if ($accountPayment->amount > 0 && $data) {
            $request = array_merge($data, [
                'job' => 'Billing',
                'email' => auth()->user()->account->email,
                'payment_id' => $accountPayment->id,
                'payment_description' => 'Payment for order #'.$this->accountOrder->number,
                'currency' => $accountPayment->currency,
                'amount' => currency($accountPayment->amount),
                'items' => $this->accountOrder->accountOrderItems->map(fn($item) => [
                    'name' => $item->name,
                    'amount' => currency($item->grand_total),
                    'currency' => $item->currency,
                    'qty' => 1,
                    'stripe_price_id' => $item->planPrice->stripe_price_id,
                ]),
            ]);

            $accountPayment->fill([
                'data' => ['pay_request' => $request],
            ])->save();

            return $request;
        }
        // no payment amount, provision straight away
        else $accountPayment->provision();
        
        if (auth()->user()->account->status === 'onboarded') return redirect()->route(app_route());
        else return redirect()->route('onboarding');
    }

    /**
     * Render
     */
    public function render()
    {
        $view = view('atom::billing.checkout');
        
        return current_route('app.billing*') ? $view : $view->layout('layouts.billing');
    }
}