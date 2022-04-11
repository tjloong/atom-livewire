<?php

namespace Jiannius\Atom\Http\Livewire\Billing;

use Livewire\Component;

class Checkout extends Component
{
    public $plan;
    public $price;
    public $cart;

    protected $queryString = ['plan', 'price'];

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->plan && !$this->price) return redirect()->route('billing');

        $this->addToCart($this->plan, $this->price);
    }

    /**
     * Get total property
     */
    public function getTotalProperty()
    {
        $currency = $this->cart->first()['currency'];
        $amount = $this->cart->sum('amount');

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
        $amount = $trial ? 0 : ($price->amount - ($price->discount ?? 0));

        $this->cart->push([
            'name' => $plan->payment_description,
            'trial' => $trial,
            'currency' => $price->currency,
            'recurring' => $price->recurring,
            'discount' => $price->discount,
            'original_amount' => $price->amount,
            'amount' => $amount,
            'plan_price_id' => $price->id,
        ]);
    }

    /**
     * Submit
     */
    public function submit($data = null)
    {
        $order = auth()->user()->account->accountOrders()->create([
            'currency' => $this->total['currency'],
            'amount' => $this->total['amount'],
        ]);

        foreach ($this->cart as $item) {
            $order->accountOrderItems()->create([
                'name' => $item['name'].($item['trial'] ? (' ('.$item['trial'].' days trial)') : ''),
                'currency' => $item['currency'],
                'amount' => $item['original_amount'],
                'discounted_amount' => $item['trial'] ? $item['original_amount'] : $item['discount'],
                'grand_total' => $item['amount'],
                'plan_price_id' => $item['plan_price_id'],
            ]);
        }

        $payment = auth()->user()->account->accountPayments()->create([
            'currency' => $order->currency,
            'amount' => $order->amount,
            'status' => $order->amount > 0 ? 'draft' : 'success',
            'account_order_id' => $order->id,
        ]);

        // payment gateway data
        if ($payment->amount > 0 && $data) {
            return array_merge($data, [
                'payment_id' => $payment->id,
                'payment_description' => 'Payment for order #'.$order->number,
                'currency' => $payment->currency,
                'amount' => $payment->amount,
            ]);
        }
        // no payment amount, provision straight away
        else if ($payment->amount <= 0) {
            $payment->provision();
        }
        
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