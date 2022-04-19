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
        $qs = [
            'job' => data_get($params, 'job'), 
            'payment_id' => data_get($params, 'payment_id'),
        ];

        \Stripe\Stripe::setApiKey($keys->secret_key);
        
        $session = \Stripe\Checkout\Session::create([
            'line_items' => collect(data_get($params, 'items', []))->map(function($item) {
                $data = [];

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

                return array_merge($data, ['quantity' => data_get($item, 'qty')]);
            })->all(),
            'mode' => $mode,
            'customer_email' => data_get($params, 'email'),
            'success_url' => route('__stripe.success', $qs),
            'cancel_url' => route('__stripe.cancel', $qs),
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
            return ($job)::dispatchNow('success', ['payment_id' => request()->query('payment_id')]);
        }
    }

    /**
     * Cancel
     */
    public function cancel()
    {
        if ($job = $this->getJob()) {
            return ($job)::dispatchNow('failed', ['payment_id' => request()->query('payment_id')]);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        // $type = 'webhook';
        // $response = request()->all();
        // $status = $this->getStatus($response);

        // OzopayFulfillment::dispatch((object)compact('type', 'status', 'response'));
    }

    /**
     * Get job
     */
    public function getJob()
    {
        $ns = (object)[
            'try' => 'App\\Jobs\\'.request()->query('job').'Provision',
            'default' => 'Jiannius\\Atom\\Jobs\\'.request()->query('job').'Provision',
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
                : site_settings('stripe_secret_key', env('STRIPE_PUBLIC_KEY')),

            'webhook_key' => $account
                ? ($account->setting->stripe_webhook_key ?? $account->setting->stripe->webhook_key ?? null)
                : site_settings('stripe_webhook_key', env('STRIPE_PUBLIC_KEY')),
        ];
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