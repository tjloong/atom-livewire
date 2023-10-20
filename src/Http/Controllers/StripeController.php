<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    // success
    public function success() : mixed
    {
        if ($job = app('stripe')->getJobHandler()) {
            return ($job)::dispatchSync('success', request()->query(), 'stripe');
        }
    }

    // cancel
    public function cancel() : mixed
    {
        if ($job = app('stripe')->getJobHandler()) {
            return ($job)::dispatchSync('cancel', request()->query(), 'stripe');
        }
    }

    // webhook
    public function webhook() : mixed
    {
        $stripe = app('stripe');
        $payload = $stripe->parseWebhookPayload();
        $job = $stripe->getJobHandler($payload);

        if ($job) {
            $event = data_get($payload, 'type');

            $isRenew = in_array($event, [
                'invoice.paid',
                'invoice.payment_failed',
            ]) && data_get($payload, 'data.object.billing_reason') === 'subscription_cycle';
          
            $isSuccess = in_array($event, [
                'checkout.session.async_payment_succeeded', 
                'invoice.paid',
            ]) || ($event === 'checkout.session.completed'
                && data_get($payload, 'data.object.payment_status') === 'paid');
    
            $isProcessing = $event === 'checkout.session.completed' 
                && data_get($payload, 'data.object.payment_status') !== 'paid';
    
            $isFailed = in_array($event, [
                'checkout.session.expired',
                'checkout.session.async_payment_failed',
                'invoice.payment_failed',
            ]);

            if ($callback = collect([
                'webhookRenewSuccess' => $isRenew && $isSuccess,
                'webhookRenewFailed' => $isRenew && $isFailed,
                'webhookSuccess' => $isSuccess,
                'webhookProcessing' => $isProcessing,
                'webhookFailed' => $isFailed,
            ])->filter()->keys()->first()) {
                ($job)::dispatchSync($callback, $payload, 'stripe');
            }
        }

        return response('OK');
    }
}