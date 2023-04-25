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
        $params = session('pay_request');
        $keys = $this->getStripeKeys(data_get($params, 'tenant_id'));
        $stripe = $this->getStripeClient($keys);
        $session = $stripe->checkout->sessions->create($this->getStripeSessionObject($params));

        return redirect($session->url);
    }

    /**
     * Success
     */
    public function success()
    {
        if ($jobhandler = $this->getJobHandler()) {
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
        if ($jobhandler = $this->getJobHandler()) {
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
        $input = @file_get_contents('php://input');
        $payload = json_decode($input);
        $metadata = $this->getMetadata($payload);
        $keys = $this->getStripeKeys(data_get($metadata, 'tenant_id'));

        $event = $this->validateStripeInput($input, data_get($keys, 'whse'));
        if (!$event) return response()->json('Unable to validate signature with the webhook signing secret.', 400);

        $jobhandler = data_get($metadata, 'job');
        $status = data_get($metadata, 'status');

        if (!$status) return response()->json('Event '.$event->type.' was not listened.', 422);
        if (!$jobhandler) return response()->json('No job was defined for event '.$event->type.'.', 422);

        ($jobhandler)::dispatch([
            'webhook' => true,
            'provider' => 'stripe',
            'metadata' => $metadata,
            'response' => (array)$payload,
        ]);

        return response('OK');
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription()
    {
        $params = request()->query();
        $redirect = data_get($params, 'redirect');

        $keys = $this->getStripeKeys(data_get($params, 'tenant_id'));
        $stripe = $this->getStripeClient($keys);
        $stripe->subscriptions->cancel(data_get($params, 'subscription_id'));

        $jobhandler = $this->getJobHandler();
        ($jobhandler)::dispatchSync($params);
        
        return $redirect
            ? redirect($redirect)
            : back();
    }

    /**
     * Get metadata
     */
    public function getMetadata($payload)
    {
        $event = data_get($payload, 'type');
        $body = data_get($payload, 'data.object');

        $status = [
            'checkout.session.completed' => data_get($body, 'payment_status') === 'paid' 
                ? 'success' 
                : 'processing',
            'checkout.session.async_payment_succeeded' => 'success',
            'checkout.session.expired' => 'failed',
            'checkout.session.async_payment_failed' => 'failed',
            'invoice.paid' => 'renew',
            'invoice.payment_failed' => 'renew-failed',
        ][$event] ?? null;
        if (!$status) return null;

        if (in_array($event, ['invoice.paid', 'invoice.payment_failed'])) {
            $lines = (array)data_get($body, 'lines.data');
            $metadata = (array)collect($lines)->pluck('metadata')->first();
        }
        else $metadata = (array)data_get($body, 'metadata');

        $customerId = data_get($body, 'customer');
        $subscriptionId = data_get($body, 'subscription');

        return array_merge($metadata, [
            'job' => $this->getJobHandler($metadata),
            'status' => $status,
            'stripe_customer_id' => $customerId,
            'stripe_subscription_id' => $subscriptionId,
        ]);
    }

    /**
     * Get job handler
     */
    public function getJobHandler($metadata = null)
    {
        $jobname = data_get($metadata, 'job') ?? request()->query('job') ?? 'StripeProvision';
        $jobhandler = collect([
            'App\\Jobs\\'.$jobname,
            'Jiannius\\Atom\\Jobs\\'.$jobname,
        ])->first(fn($ns) => class_exists($ns));

        return $jobhandler;
    }

    /**
     * Get stripe client
     */
    public function getStripeClient($keys)
    {
        $sk = data_get($keys, 'sk');

        return new \Stripe\StripeClient($sk);
    }

    /**
     * Get stripe keys
     */
    public function getStripeKeys($tenantId = null)
    {
        $tenant = $tenantId ? model('tenant')->find($tenantId) : null;
        $settings = optional($tenant)->settings;

        $pk = $tenant
            ? data_get($settings, 'stripe_public_key') ?? data_get(optional($settings->stripe), 'public_key')
            : settings('stripe_public_key', env('STRIPE_PUBLIC_KEY'));

        $sk = $tenant
            ? data_get($settings, 'stripe_secret_key') ?? data_get(optional($settings->stripe), 'secret_key')
            : settings('stripe_secret_key', env('STRIPE_SECRET_KEY'));

        $whse = $tenant
            ? data_get($settings, 'stripe_webhook_signing_secret') ?? data_get(optional($settings->stripe), 'webhook_signing_secret')
            : settings('stripe_webhook_signing_secret', env('STRIPE_WEBHOOK_SIGNING_SECRET'));

        return compact('pk', 'sk', 'whse');
    }

    /**
     * Get stripe session object
     */
    public function getStripeSessionObject($params)
    {
        $hasRecurringItems = collect(data_get($params, 'items'))->search(fn($item) => !empty(data_get($item, 'recurring')));
        $mode = is_numeric($hasRecurringItems) ? 'subscription' : 'payment';

        $metadata = [
            'job' => data_get($params, 'job'), 
            'payment_id' => data_get($params, 'payment_id'),
            'tenant_id' => data_get($params, 'tenant_id'),
        ];

        $lineItems = collect(data_get($params, 'items', []))->map(function($item) {
            $recurring = [
                'interval' => data_get($item, 'recurring.interval'),
                'interval_count' => data_get($item, 'recurring.count'),
            ];

            $unitAmount = str(data_get($item, 'amount'))
                ->replace('.', '')
                ->replace(',', '')
                ->toString();

            return [
                'quantity' => data_get($item, 'qty'),
                'price_data' => array_filter([
                    'currency' => data_get($item, 'currency'),
                    'product_data' => ['name' => data_get($item, 'name')],
                    'unit_amount' => $unitAmount,
                    'recurring' => $recurring['interval_count'] && $recurring['interval']
                        ? $recurring
                        : null,
                ]),
            ];
        })->all();

        $email = data_get($params, 'customer.email');
        $customerId = data_get($params, 'customer.stripe_customer_id');
        $subscriptionData = $mode === 'subscription' ? ['metadata' => $metadata] : null;

        return array_filter([
            'mode' => $mode,
            'metadata' => $metadata,
            'customer_email' => $customerId ? null : $email,
            'customer' => $customerId,
            'subscription_data' => $subscriptionData,
            'success_url' => route('__stripe.success', $metadata),
            'cancel_url' => route('__stripe.cancel', $metadata),
            'line_items' => $lineItems,
        ]);
    }

    /**
     * Validation stripe input
     */
    public function validateStripeInput($input, $whse)
    {
        $sigheader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        
        try {
            $event = \Stripe\Webhook::constructEvent($input, $sigheader, $whse);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            $event = false;
        }

        return $event;
    }
}