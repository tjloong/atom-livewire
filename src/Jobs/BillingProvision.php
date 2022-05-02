<?php

namespace Jiannius\Atom\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BillingProvision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $status;
    public $provider;
    public $response;
    public $isWebhook;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->status = data_get($params, 'status');
        $this->provider = data_get($params, 'provider');
        $this->response = data_get($params, 'pay_response');
        $this->isWebhook = data_get($params, 'webhook', false);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accountPayment = $this->getAccountPayment();

        if (
            ($this->isWebhook && (
                $this->status !== 'processing'
                || ($this->status === 'processing' && ($accountPayment->status === 'draft' || !$accountPayment->status))
            ))
            || (!$this->isWebhook && ($accountPayment->status === 'draft' || !$accountPayment->status))
        ) {
            $accountPayment->fill([
                'status' => $this->status,
                'data' => array_merge((array)$accountPayment->data, ['pay_response' => $this->response]),
            ])->save();
        }

        $accountPayment->provision();

        if (!$this->isWebhook) return redirect()->route('billing', ['payment_status' => $this->status]);
    }

    /**
     * Get account payment
     */
    public function getAccountPayment()
    {
        if ($this->provider === 'stripe') {
            $paymentId = $this->isWebhook
                ? data_get($this->response, 'data.object.metadata.payment_id')
                : data_get($this->response, 'payment_id');
        }
        else if ($this->provider === 'gkash') {
            $paymentId = data_get($this->response, 'payment_id');
        }

        return model('account_payment')->findOrFail($paymentId);
    }
}