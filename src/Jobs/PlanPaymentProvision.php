<?php

namespace Jiannius\Atom\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PlanPaymentProvision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $status;
    public $payment;
    public $provider;
    public $metadata;
    public $isWebhook;
    public $payResponse;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->metadata = data_get($params, 'metadata');
        $this->payment = model('plan_payment')->findOrFail(data_get($this->metadata, 'payment_id'));
        $this->status = data_get($this->metadata, 'status');
        $this->provider = data_get($params, 'provider');
        $this->isWebhook = data_get($params, 'webhook', false);
        $this->payResponse = data_get($params, 'response');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isWebhook) return $this->handleWebhook();

        if (in_array($this->payment->status, ['draft', null])) {
            $this->provision();
        }

        return redirect()->route('app.settings', ['billing']);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook()
    {
        if (
            in_array($this->status, ['success', 'failed'])
            || ($this->status === 'processing' && in_array($this->payment->status, ['draft', null]))
        ) {
            $this->provision();
        }
        // renewal (only application to stripe auto billing)
        else if (str($this->status)->startsWith('renew')) {
            $billingReason = data_get($this->payResponse, 'data.object.billing_reason');
            if ($billingReason !== 'subscription_cycle') {
                logger('not going to renew');
                return;
            }

            $this->duplicate();
            $this->provision();
        }
    }

    /**
     * Provision payment
     */
    public function provision()
    {
        $this->payment->fill([
            'status' => $this->status === 'renew-failed' ? 'failed' : $this->status,
            'data' => array_merge((array)$this->payment->data, [
                'metadata' => $this->metadata,
                'pay_response' => $this->payResponse,
            ]),
        ])->save();

        $this->createSubscription();
    }

    /**
     * Duplicate payment
     */
    public function duplicate()
    {
        $newpayment = model('plan_payment')->create([
            'currency' => $this->payment->currency,
            'amount' => $this->payment->amount,
            'mode' => 'stripe',
            'price_id' => $this->payment->price_id,
        ]);

        $this->payment = $newpayment;

        // $order = $this->payment->order;

        // // only use recurring order items
        // $items = $order->items
        //     ->filter(fn($item) => is_numeric(data_get($item->data, 'recurring.count')));

        // $neworder = model('plan_order')->create([
        //     'currency' => $order->currency,
        //     'amount' => $items->sum('grand_total'),
        //     'data' => $order->data,
        //     'user_id' => $order->user_id,
        // ]);

        // foreach ($items as $item) {
        //     $neworder->items()->create([
        //         'name' => $item->name,
        //         'currency' => $item->currency,
        //         'amount' => $item->amount,
        //         'discounted_amount' => $item->discounted_amount,
        //         'grand_total' => $item->grand_total,
        //         'data' => $item->data,
        //         'plan_price_id' => $item->plan_price_id,
        //     ]);
        // }

        // $this->payment = $neworder->payments()->create([
        //     'currency' => $this->payment->currency,
        //     'amount' => $neworder->amount,
        //     'status' => $neworder->amount > 0 ? 'draft' : 'success',
        //     'provider' => 'stripe',
        //     'status' => $this->status === 'renew-failed' ? 'failed' : 'success',
        //     'data' => [
        //         'metadata' => $this->metadata,
        //         'pay_response' => $this->payResponse,
        //     ],
        //     'plan_order_id' => $neworder->id,
        // ]);
    }

    /**
     * Create subscription
     */
    public function createSubscription()
    {
        if ($this->payment->status !== 'success') return;

        // stripe info
        $stripeData = array_filter([
            'stripe_customer_id' => data_get($this->metadata, 'stripe_customer_id'),
            'stripe_subscription_id' => data_get($this->metadata, 'stripe_subscription_id'),
        ]);

        $subscription = model('plan_subscription')->create([
            'user_id' => $this->payment->user_id,
            'price_id' => $this->payment->price_id,
            'data' => $stripeData,
        ]);

        $this->payment->fill([
            'subscription_id' => $subscription->id,
        ])->save();

        // foreach ($this->payment->order->items as $item) {
        //     if ($subscription = model('plan_subscription')->firstWhere('plan_order_item_id', $item->id)) {
        //         $subscription->fill(['data' => array_merge((array)$subscription->data, $stripeData)])->save();
        //     }
        //     else {
        //         $price = $item->price;
    
        //         // trial
        //         if ($price->plan->trial && !$this->payment->order->user->isSubscribedToPlan($price)) {
        //             $fields = [
        //                 'is_trial' => true,
        //                 'start_at' => $this->payment->created_at,
        //                 'expired_at' => $this->payment->created_at->addDays($price->plan->trial),
        //             ];
        //         }
        //         // non-trial
        //         else {
        //             $lastSubscription = $this->payment->order->user->subscriptions()
        //                 ->where('plan_price_id', $price->id)
        //                 ->latest()
        //                 ->first();

        //             $start = $lastSubscription 
        //                 ? $lastSubscription->expired_at->addSecond() 
        //                 : $this->payment->created_at;

        //             $end = $price->is_lifetime
        //                 ? null
        //                 : $start->copy()->addMonths($price->expired_after);
    
        //             $fields = [
        //                 'is_trial' => false,
        //                 'start_at' => $start,
        //                 'expired_at' => $end,
        //             ];
        //         }
                    
        //         $this->payment->order->user->subscriptions()->create(array_merge(
        //             $fields, 
        //             ['data' => $stripeData],
        //             [
        //                 'plan_order_item_id' => $item->id,
        //                 'plan_price_id' => $price->id,
        //             ]
        //         ));
        //     }
        // }

        // $this->payment->fill(['provisioned_at' => now()])->save();
    }
}