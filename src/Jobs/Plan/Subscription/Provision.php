<?php

namespace Jiannius\Atom\Jobs\Plan\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Provision implements ShouldQueue
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
     */
    public function handle()
    {
        if ($this->isWebhook) return $this->handleWebhook();

        if (in_array($this->payment->status, ['draft', null])) {
            $this->provision();
        }

        return redirect($this->payment->user->home())->with('plan-payment', $this->payment->status);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook(): void
    {
        if (
            in_array($this->status, ['success', 'failed'])
            || ($this->status === 'processing' && in_array($this->payment->status, ['draft', null]))
        ) {
            $this->provision();
        }
        // renewal (only application to stripe auto billing)
        else if (str($this->status)->startsWith('renew')) {
            logger('renewing payment');

            $billingReason = data_get($this->payResponse, 'data.object.billing_reason');

            if ($billingReason !== 'subscription_cycle') logger('not going to renew');
            else {
                $this->duplicate();
                $this->provision();
            }
        }
    }

    /**
     * Provision payment
     */
    public function provision(): void
    {
        $this->payment->fill([
            'status' => $this->status === 'renew-failed' ? 'failed' : $this->status,
            'data' => array_merge((array)$this->payment->data, [
                'metadata' => $this->metadata,
                'pay_response' => $this->payResponse,
            ]),
        ])->save();

        if (
            in_array($this->payment->status, ['renew', 'success'])
            && ($subscription = $this->payment->subscription)
            && !$subscription->provisioned_at
        ) {
            // terminate less expensive relatives
            if ($this->payment->status === 'success') {
                $subscription->getTerminationQueue()->each(fn($relative) => $relative->terminate());
            }

            $subscription->fill([
                'data' => [
                    'stripe_customer_id' => data_get($this->metadata, 'stripe_customer_id'),
                    'stripe_subscription_id' => data_get($this->metadata, 'stripe_subscription_id'),
                ],
                'provisioned_at' => now(),
            ])->save();
        }
    }

    /**
     * Duplicate payment
     */
    public function duplicate(): void
    {
        $newpayment = model('plan_payment')->create([
            'currency' => $this->payment->currency,
            'amount' => $this->payment->amount,
            'mode' => 'stripe',
            'description' => $this->payment->description,
            'status' => 'draft',
            'user_id' => $this->payment->user_id,
        ]);

        $newsubscription = model('plan_subscription')->fill([
            'user_id' => $this->payment->subscription->user_id,
            'price_id' => $this->payment->subscription->price_id,
            'payment_id' => $newpayment->id,
        ]);

        $newsubscription->setValidity();
        $newsubscription->save();

        $this->payment = $newpayment;
    }
}