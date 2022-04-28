<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    /**
     * Create request signature
     */
    public function sign()
    {
        $params = request()->input('params');
        $account = model('account')->find(request()->input('account_id'));
        $keys = $this->getKeys($account);
        $mode = $this->hasSubscriptionItems(data_get($params, 'items')) ? 'subscription' : 'payment';
        $metadata = [
            'job' => data_get($params, 'job'), 
            'payment_id' => data_get($params, 'payment_id'),
            'account_id' => optional($account)->id,
        ];

        \Stripe\Stripe::setApiKey($keys->secret_key);
        
        $session = \Stripe\Checkout\Session::create([
            'line_items' => collect(data_get($params, 'items', []))->map(function($item) {
                $data = ['quantity' => data_get($item, 'qty')];

                if ($stripePriceId = data_get($item, 'stripe_price_id')) {
                    $data['price'] = $stripePriceId;
                }
                else {
                    $data['price_data'] = [
                        'currency' => data_get($item, 'currency'),
                        'product_data' => ['name' => data_get($item, 'name')],
                        'unit_amount' => str(data_get($item, 'amount'))->replace('.', '')->replace(',', ''),    
                    ];
                }

                return $data;
            })->all(),
            'mode' => $mode,
            'metadata' => $metadata,
            'customer_email' => data_get($params, 'email'),
            'success_url' => route('__stripe.success', $metadata),
            'cancel_url' => route('__stripe.cancel', $metadata),
        ]);

        return response()->json([
            'endpoint' => $session->url,
            'endpoint_method' => 'get',
        ]);
    }

    /**
     * Success
     */
    public function success()
    {
        if ($job = $this->getJob()) {
            return ($job)::dispatchNow([
                'status' => 'success', 
                'provider' => 'stripe',
                'pay_response' => request()->query(),
            ]);
        }
    }

    /**
     * Cancel
     */
    public function cancel()
    {
        if ($job = $this->getJob()) {
            return ($job)::dispatchNow([
                'status' => 'failed', 
                'provider' => 'stripe',
                'pay_response' => request()->query(),
            ]);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        $payload = @file_get_contents('php://input');
        $metadata = data_get(json_decode($payload), 'data.object.metadata');
        $account = model('account')->find(data_get($metadata, 'account_id'));
        $keys = $this->getKeys($account);
        $job = $this->getJob($metadata);

        if ($keys->webhook_signing_secret) {
            $sigheader = $_SERVER['HTTP_STRIPE_SIGNATURE'];

            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigheader, $keys->webhook_signing_secret);
            } catch(\Stripe\Exception\SignatureVerificationException $e) {
                abort(400, 'Stripe webhook error while validating signature.');
            }
        }
        else abort(400, 'No webhook signing secret.');

        if ($job) {
            ($job)::dispatch([
                'webhook' => true,
                'status' => $this->getStatus($event), 
                'provider' => 'stripe',
                'pay_response' => json_decode($payload),
            ]);

            return response(200);
        }
        else abort(500, 'No stripe provisioning job.');
    }

    /**
     * Get job
     */
    public function getJob($metadata = null)
    {
        $name = data_get($metadata, 'job') ?? request()->query('job') ?? 'Stripe';
        $ns = (object)[
            'try' => 'App\\Jobs\\'.$name.'Provision',
            'default' => 'Jiannius\\Atom\\Jobs\\'.$name.'Provision',
        ];

        if (class_exists($ns->try)) return $ns->try;
        else if (class_exists($ns->default)) return $ns->default;
    }

    /**
     * Get Keys
     */
    public function getKeys($account = null)
    {
        return (object)[
            'public_key' => $account
                ? ($account->setting->stripe_public_key ?? $account->setting->stripe->public_key ?? null)
                : site_settings('stripe_public_key', env('STRIPE_PUBLIC_KEY')),

            'secret_key' => $account
                ? ($account->setting->stripe_secret_key ?? $account->setting->stripe->secret_key ?? null)
                : site_settings('stripe_secret_key', env('STRIPE_SECRET_KEY')),

            'webhook_signing_secret' => $account
                ? ($account->setting->stripe_webhook_signing_secret ?? $account->setting->stripe->webhook_signing_secret ?? null)
                : site_settings('stripe_webhook_signing_secret', env('STRIPE_WEBHOOK_SIGNING_SECRET')),
        ];
    }

    /**
     * Get status
     */
    public function getStatus($event)
    {
        if ($event->type === 'checkout.session.completed') {
            if ($event->data->object->payment_status === 'paid') return 'success';
            else return 'processing';
        }
        else if ($event->type === 'checkout.session.async_payment_succeeded') return 'success';
        else return 'failed';
    }

    /**
     * Check has subscription items
     */
    public function hasSubscriptionItems($items)
    {
        $search = collect($items)->search(function($item) {
            $stripePriceId = $item['stripe_price_id'] ?? null;
            $isSubscription = $item['is_subscription'] ?? false;

            return $stripePriceId && $isSubscription;
        });

        return $search ? true : false;
    }
}