<?php

namespace Jiannius\Atom\Services;

class Stripe
{
    public $client;
    public $credentials;

    /**
     * Constructor
     */
    public function __construct($data = null)
    {
        $this->credentials = $this->getCredentials($data);
        $this->client = $this->getClient();
    }

    /**
     * Get credentials
     */
    public function getCredentials($data = null)
    {
        $tenant = data_get($data, 'tenant_id') ? model('tenant')->find(data_get($data, 'tenant_id')) : tenant();

        $pk = data_get($data, 'pk') ?? ($tenant 
            ? (tenant('settings.stripe_public_key', null, $tenant) ?? tenant('settings.stripe.public_key', null, $tenant))
            : settings('stripe_public_key') ?? settings('stripe.public_key') ?? env('STRIPE_PUBLIC_KEY')
        );

        $sk = data_get($data, 'sk') ?? ($tenant
            ? (tenant('settings.stripe_secret_key', null, $tenant) ?? tenant('settings.stripe.secret_key', null, $tenant))
            : settings('stripe_secret_key') ?? settings('stripe.secret_key') ?? env('STRIPE_SECRET_KEY')
        );

        $whse = data_get($data, 'whse') ?? ($tenant
            ? (tenant('settings.stripe_webhook_signing_secret', null, $tenant) ?? tenant('settings.stripe.webhook_signing_secret', null, $tenant))
            : settings('stripe_webhook_signing_secret') ?? settings('stripe.webhook_signing_secret') ?? env('STRIPE_WEBHOOK_SIGNING_SECRET')
        );

        return compact('pk', 'sk', 'whse');
    }

    /**
     * Get client
     */
    public function getClient()
    {
        $sk = data_get($this->credentials, 'sk');

        return new \Stripe\StripeClient($sk);
    }

    /**
     * Handle webhook request
     */
    public function getWebhookRequest()
    {
        $input = @file_get_contents('php://input');
        $payload = json_decode($input, true);
        $metadata = $this->parsePayload($payload);

        // validate the payload signature
        $credentials = $this->getCredentials($metadata);
        $whse = data_get($credentials, 'whse');
        $event = $this->validateWebhookInput($input, $whse);

        if (!$event) logger('Unable to validate signature with the webhook signing secret.');
        else if (!data_get($metadata, 'status')) info('Event '.$event->type.' was not listened.');
        else if (!data_get($metadata, 'job')) info('No job was defined for event '.$event->type.'.');
        else return compact('metadata', 'event', 'payload');
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
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        return $jobhandler;
    }

    /**
     * Parse payload
     */
    public function parsePayload($payload)
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
     * Validation webhook input
     */
    public function validateWebhookInput($input, $whse)
    {
        $sigheader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        
        try {
            $event = \Stripe\Webhook::constructEvent($input, $sigheader, $whse);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            $event = false;
        }

        return $event;
    }

    /**
     * Sample params
     */
    public function sample()
    {
        return [
            'customer' => 'cus_NzHqNbIaJ56Juq',
            'customer_email' => 'test@sign.up',
            'mode' => 'subscription',
            'metadata' => [
                'job' => 'PlanSubscriptionProvision',
                'payment_id' => 1,
                'tenant_id' => null,
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
                    'job' => 'PlanSubscriptionProvision',
                    'payment_id' => 1,
                    'tenant_id' => null,
                ],
            ],
            'success_url' => route('__stripe.success'),
            'cancel_url' => route('__stripe.cancel'),
        ];
    }

    /**
     * Checkout
     */
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

    /**
     * Cancel subscription
     */
    public function cancelSubscription($id)
    {
        $this->client->subscriptions->cancel($id);
    }
}