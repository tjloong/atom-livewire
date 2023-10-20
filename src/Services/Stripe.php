<?php

namespace Jiannius\Atom\Services;

class Stripe
{
    public $client;
    public $credentials;

    // constructor
    public function __construct()
    {
        $this->setCredentials();
        $this->setClient();
    }

    // set credentials
    public function setCredentials($credentials = null) : void
    {
        $this->credentials = collect($credentials ?? [
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

    // get job handler
    public function getJobHandler($payload = null) : mixed
    {
        // if payload is from webhook renewal
        if (in_array(data_get($payload, 'type'), ['invoice.paid', 'invoice.payment_failed'])) {
            $jobname = data_get($payload, 'data.object.lines.data.0.metadata.job');
        }
        else {
            $jobname = data_get($payload, 'data.object.metadata.job')
                ?? data_get($payload, 'job')
                ?? request()->query('job')
                ?? 'StripeProvision';
        }

        $jobhandler = collect([
            'App\\Jobs\\'.$jobname,
            'Jiannius\\Atom\\Jobs\\'.$jobname,
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        return $jobhandler;
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
    public function sample()
    {
        return [
            'customer' => 'cus_NzHqNbIaJ56Juq',
            'customer_email' => 'test@sign.up',
            'mode' => 'subscription',
            'metadata' => [
                'job' => 'Subscription\Provision',
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
                    'job' => 'Subscription\Provision',
                    'payment_id' => 1,
                ],
            ],
            'success_url' => route('__stripe.success'),
            'cancel_url' => route('__stripe.cancel'),
        ];
    }

    // checkout
    public function checkout($params)
    {
        // change product amount to proper format (eg. 25.00 -> 2500)
        $params['line_items'] = collect($params['line_items'])->map(fn($item) =>
            collect($item)->replaceRecursive([
                'price_data' => [
                    'unit_amount' => str(data_get($item, 'price_data.unit_amount'))
                        ->replace('.', '')
                        ->replace(',', '')
                        ->toString(),
                ],
            ])->toArray()
        )->toArray();

        // create stripe checkout session
        $session = $this->client->checkout->sessions->create($params);

        return redirect($session->url);
    }

    // cancel
    public function cancelSubscription($id)
    {
        $this->client->subscriptions->cancel($id);
    }
}