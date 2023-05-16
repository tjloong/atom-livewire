<?php

namespace Jiannius\Atom\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PlanSubscriptionProvision implements ShouldQueue
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

        return redirect($this->payment->user->home())->with('plan-payment', $this->payment->status);
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

        if ($this->payment->status === 'success') {
            $this->payment->subscriptions()->whereNull('provisioned_at')->get()->each(function($subscription) {
                // terminate less expensive relatives
                $subscription->getTerminationQueue()->each(fn($relative) => $relative->terminate());
    
                $subscription->fill([
                    'data' => [
                        'stripe_customer_id' => data_get($this->metadata, 'stripe_customer_id'),
                        'stripe_subscription_id' => data_get($this->metadata, 'stripe_subscription_id'),
                    ],
                    'provisioned_at' => now(),
                ])->save();
            });
        }
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
            'user_id' => $this->payment->user_id,
        ]);

        foreach ($this->payment->subscriptions()->get() as $subscription) {
            $newsubscription = model('plan_subscription')->create([
                'user_id' => $subscription->user_id,
                'price_id' => $subscription->price_id,
                'payment_id' => $newpayment->id,
            ]);

            $newsubscription->setValidity();
        }

        $this->payment = $newpayment;
    }
}