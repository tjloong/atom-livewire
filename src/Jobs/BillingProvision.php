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
    public $params;
    public $isWebhook;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($status, $params = [], $isWebhook = false)
    {
        $this->status = $status;
        $this->params = $params;
        $this->isWebhook = $isWebhook;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payment = model('account_payment')->findOrFail(data_get($this->params, 'payment_id'));

        if ($this->isWebhook || !$payment->status || $payment->status === 'draft') {
            $payment->status = $this->status;
            $payment->data = array_merge((array)$payment->data, ['response' => $this->params]);
            $payment->save();
        }

        if ($payment->status === 'success') $payment->provision();

        if (!$this->isWebhook) return redirect()->route('billing', ['payment_status' => $this->status]);
    }
}