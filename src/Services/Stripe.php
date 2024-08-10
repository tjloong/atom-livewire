<?php

namespace Jiannius\Atom\Services;

class Stripe
{
    public $client;
    public $credentials;

    // constructor
    public function __construct($credentials = [])
    {
        $this->setCredentials($credentials);
        $this->setClient();
    }

    // set credentials
    public function setCredentials($credentials = []) : void
    {
        $this->credentials = collect($credentials ?: [
            'public_key' => settings('stripe_public_key'),
            'secret_key' => settings('stripe_secret_key'),
            'webhook_signing_secret' => settings('stripe_webhook_signing_secret'),
        ]);
    }

    // set client
    public function setClient() : void
    {
        $this->client = new \Stripe\StripeClient($this->credentials->get('secret_key'));
    }

    // get webhook status
    public function getWebhookStatus() : mixed
    {
        $payload = $this->parseWebhookPayload();
        $event = get($payload, 'type');

        $isRenew = in_array($event, [
            'invoice.paid',
            'invoice.payment_failed',
        ]) && get($payload, 'data.object.billing_reason') === 'subscription_cycle';

        $isFailed = in_array($event, [
            'checkout.session.expired',
            'checkout.session.async_payment_failed',
            'invoice.payment_failed',
        ]);

        $isSuccess = in_array($event, [
            'checkout.session.async_payment_succeeded', 
            'invoice.paid',
        ]) || (
            $event === 'checkout.session.completed'
            && get($payload, 'data.object.payment_status') === 'paid'
        );

        $isProcessing = $event === 'checkout.session.completed' 
            && get($payload, 'data.object.payment_status') !== 'paid';

        return pick([
            'renew-failed' => $isRenew && $isFailed,
            'renew-success' => $isRenew && $isSuccess,
            'failed' => $isFailed,
            'processing' => $isProcessing,
            'success' => $isSuccess,
        ]);
    }

    // parse webhook payload
    public function parseWebhookPayload() : mixed
    {
        $input = @file_get_contents('php://input');

        if ($this->validateWebhookInput($input)) {
            return json_decode($input, true);
        }

        return null;
    }

    // validate webhook input
    public function validateWebhookInput($input) : bool
    {
        $key = $this->credentials->get('webhook_signing_secret');
        $sigheader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        
        try {
            $event = \Stripe\Webhook::constructEvent($input, $sigheader, $key);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            $event = false;
        }

        if (!$event) {
            logger('Unable to validate signature with the webhook signing secret.');
            return false;
        }

        return true;
    }

    // sample params
    public function sample() : array
    {
        return [
            'customer' => 'cus_NzHqNbIaJ56Juq',
            'customer_email' => 'test@sign.up',
            'mode' => 'subscription',
            'metadata' => [
                'payment_id' => 1,
            ],
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => 'USD',
                        'product_data' => [
                            'name' => 'HumbleBear Pro Plan Monthly',
                        ],
                        'unit_amount' => '1500',
                        'recurring' => [
                            'interval_count' => 1,
                            'interval' => 'month',
                        ],
                    ],
                ],
            ],
            'subscription_data' => [
                'metadata' => [
                    'payment_id' => 1,
                ],
            ],
            'success_url' => route('__stripe.success'),
            'cancel_url' => route('__stripe.cancel'),
        ];
    }

    // checkout
    public function checkout($params) : mixed
    {
        $params = [
            ...$params,
            // change product amount to proper format (eg. 25.00 -> 2500)
            'line_items' => collect(get($params, 'line_items'))
                ->map(fn($item) => collect($item)->replaceRecursive([
                    'price_data' => [
                        'unit_amount' => str(data_get($item, 'price_data.unit_amount'))
                            ->replace('.', '')
                            ->replace(',', '')
                            ->toString(),
                    ],
                ])->toArray())
                ->toArray(),
            'success_url' => route('__stripe.success', get($params, 'metadata', [])),
            'cancel_url' => route('__stripe.cancel', get($params, 'metadata', [])),
        ];

        // create stripe checkout session
        $session = $this->client->checkout->sessions->create($params);

        return redirect($session->url);
    }

    // cancel
    public function cancelSubscription($id) : void
    {
        $this->client->subscriptions->cancel($id);
    }

    // create webhook
    public function createWebhook() : mixed
    {
        $url = route('__stripe.webhook');
        $webhooks = $this->client->webhookEndpoints->all();
        
        if ($webhook = collect($webhooks->data)->where('url', $url)->first()) {
            $this->client->webhookEndpoints->delete(get($webhook, 'id'));
        }

        $webhook = $this->client->webhookEndpoints->create([
            'url' => $url,
            'enabled_events' => [
                'checkout.session.async_payment_failed',
                'checkout.session.async_payment_succeeded',
                'checkout.session.completed',
                'checkout.session.expired',
            ],
        ]);

        return get($webhook, 'secret');
    }

    // test
    public function test() : array
    {
        try {
            $this->client->accounts->all();

            return [
                'success' => true,
                'error' => null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}