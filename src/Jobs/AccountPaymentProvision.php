<?php

namespace Jiannius\Atom\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AccountPaymentProvision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $status;
    public $provider;
    public $metadata;
    public $isWebhook;
    public $payResponse;
    public $accountPayment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->provider = data_get($params, 'provider');
        $this->metadata = data_get($params, 'metadata');
        $this->isWebhook = data_get($params, 'webhook', false);
        $this->payResponse = data_get($params, 'response');
        $this->status = data_get($this->metadata, 'status');
        $this->accountPayment = model('account_payment')
            ->findOrFail(data_get($this->metadata, 'payment_id'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isWebhook) return $this->handleWebhook();

        if (in_array($this->accountPayment->status, ['draft', null])) {
            $this->provisionAccountPayment();
        }

        return redirect()->route('app.billing.home', ['status' => $this->status]);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook()
    {
        if (
            in_array($this->status, ['success', 'failed'])
            || ($this->status === 'processing' && in_array($this->accountPayment->status, ['draft', null]))
        ) {
            $this->provisionAccountPayment();
        }
        // renewal (only application to stripe auto billing)
        else if (str($this->status)->startsWith('renew')) {
            $billingReason = data_get($this->payResponse, 'data.object.billing_reason');
            if ($billingReason !== 'subscription_cycle') {
                logger('not going to renew');
                return;
            }

            $newAccountPayment = $this->duplicateAccountPayment();
            $newAccountPayment->provision($this->metadata);
        }
    }

    /**
     * Provision account payment
     */
    public function provisionAccountPayment()
    {
        $this->accountPayment->fill([
            'status' => $this->status,
            'data' => array_merge((array)$this->accountPayment->data, [
                'metadata' => $this->metadata,
                'pay_response' => $this->payResponse,
            ]),
        ])->save();

        $this->accountPayment->provision($this->metadata);
    }

    /**
     * Duplicate account payment
     */
    public function duplicateAccountPayment()
    {
        $accountOrder = $this->accountPayment->order;

        // only use recurring order items
        $accountOrderItems = $accountOrder->items
            ->filter(fn($item) => is_numeric(data_get($item->data, 'recurring.count')));

        $newAccountOrder = model('account_order')->create([
            'currency' => $accountOrder->currency,
            'amount' => $accountOrderItems->sum('grand_total'),
            'data' => $accountOrder->data,
            'account_id' => $accountOrder->account_id,
        ]);

        foreach ($accountOrderItems as $item) {
            $newAccountOrder->items()->create([
                'name' => $item->name,
                'currency' => $item->currency,
                'amount' => $item->amount,
                'discounted_amount' => $item->discounted_amount,
                'grand_total' => $item->grand_total,
                'data' => $item->data,
                'plan_price_id' => $item->plan_price_id,
            ]);
        }

        $newAccountPayment = $newAccountOrder->payments()->create([
            'currency' => $this->accountPayment->currency,
            'amount' => $newAccountOrder->amount,
            'status' => $newAccountOrder->amount > 0 ? 'draft' : 'success',
            'provider' => 'stripe',
            'status' => $this->status === 'renew-failed' ? 'failed' : 'success',
            'data' => [
                'metadata' => $this->metadata,
                'pay_response' => $this->payResponse,
            ],
            'account_id' => $this->accountPayment->account_id,
            'account_order_id' => $newAccountOrder->id,
        ]);

        return $newAccountPayment;
    }
}