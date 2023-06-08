<?php

namespace Jiannius\Atom\Jobs\Shop;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderProvision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $status;
    public $payment;
    public $channel;
    public $metadata;
    public $isWebhook;
    public $payResponse;

    /**
     * Create a new job instance.
     */
    public function __construct($params)
    {
        $this->metadata = data_get($params, 'metadata');
        $this->payment = model('payment')->findOrFail(data_get($this->metadata, 'payment_id'));
        $this->status = data_get($this->metadata, 'status');
        $this->channel = data_get($params, 'channel');
        $this->isWebhook = data_get($params, 'webhook', false);
        $this->payResponse = data_get($params, 'response');        
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->provision();

        if (!$this->isWebhook) {
            return redirect()->route('web.thank', ['shop/payment', 'payment' => (string) $this->payment->ulid]);
        }
    }

    /**
     * Provision
     */
    public function provision()
    {
        if (!$this->isWebhook && !in_array($this->payment->status, ['draft', null])) return null;

        $this->payment->fill([
            'mode' => $this->payment->mode ?? $this->channel,
            'status' => $this->status,
            'data' => array_merge((array)$this->payment->data, [
                'metadata' => $this->metadata,
                'pay_response' => $this->payResponse,
            ]),
        ])->save();

        optional($this->payment->order)->touch();
    }
}