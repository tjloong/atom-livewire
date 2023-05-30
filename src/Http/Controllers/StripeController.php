<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    /**
     * Checkout
     */
    public function checkout()
    {
        if ($params = session('pay_request')) {
            $checkout = stripe($params)->checkout($params);

            return redirect($checkout->url);
        }

        return back();
    }

    /**
     * Success
     */
    public function success()
    {
        if ($jobhandler = stripe()->getJobHandler()) {
            return ($jobhandler)::dispatchSync([
                'provider' => 'stripe',
                'metadata' => array_merge(request()->query(),[
                    'job' => $jobhandler,
                    'status' => 'success',
                ]),
            ]);
        }
    }

    /**
     * Cancel
     */
    public function cancel()
    {
        if ($jobhandler = stripe()->getJobHandler()) {
            return ($jobhandler)::dispatchSync([
                'provider' => 'stripe',
                'metadata' => array_merge(request()->query(), [
                    'job' => $jobhandler,
                    'status' => 'failed',
                ]),
            ]);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        if ($webhook = stripe()->getWebhookRequest()) {
            $metadata = data_get($webhook, 'metadata');
            $payload = data_get($webhook, 'payload');
            $jobhandler = data_get($metadata, 'job');

            ($jobhandler)::dispatchSync([
                'webhook' => true,
                'provider' => 'stripe',
                'metadata' => $metadata,
                'response' => $payload,
            ]);
        }

        return response('OK');
    }
}