<?php

namespace Jiannius\Atom\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelAccountSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stripeSubscriptionId = data_get($this->params, 'subscription_id');
        $accountSubscriptions = model('account_subscription')
            ->where('data->stripe_subscription_id', $stripeSubscriptionId)
            ->get();

        $accountSubscriptions->each(fn($subs) => $subs->fill([
            'data' => array_merge((array)$subs->data, [
                'stripe_subscription_id' => null,
            ]),
        ])->save());
    }
}