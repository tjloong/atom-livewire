<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PaymentGateway extends Component
{
    public $value;
    public $providers;

    /**
     * Contructor
     */
    public function __construct(
        $value = [],
        $logos = [],
        $tenant = null,
    ) {
        $this->value = $value;

        $logos = array_merge([
            'ozopay' => ['ozopay', 'fpx', 'visa', 'master', 'tng'],
            'gkash' => ['fpx', 'tng'],
            'stripe' => ['visa', 'master'],
            'ipay' => ['ipay'],    
        ], $logos);

        $this->providers = collect(config('atom.payment_gateway'))
            ->map(fn($provider) => [
                'name' => $provider,
                'keys' => $this->{'get'.str()->headline($provider).'Keys'}($tenant),
                'logos' => data_get($logos, $provider, []),
            ])
            ->filter(fn($provider) => !empty(data_get($provider, 'keys')));

    }

    /**
     * Get stripe keys
     */
    public function getStripeKeys($tenant)
    {
        if ($tenant) {
            $settings = $tenant->settings;
            $pk = data_get($settings, 'stripe_public_key') ?? data_get(optional($settings->stripe), 'public_key');
            $sk = data_get($settings, 'stripe_secret_key') ?? data_get(optional($settings->stripe), 'secret_key');
        }
        else {
            $pk = settings('stripe_public_key', env('STRIPE_PUBLIC_KEY'));
            $sk = settings('stripe_secret_key', env('STRIPE_SECRET_KEY'));
        }

        return $pk && $sk ? compact('pk', 'sk') : null;
    }

    /**
     * Get ozopay keys
     */
    public function getOzopayKeys($tenant)
    {
        if ($tenant) {
            $settings = $tenant->settings;
            $tid = data_get($settings, 'ozopay_tid') ?? data_get(optional($settings->ozopay), 'tid');
            $sec = data_get($settings, 'ozopay_secret') ?? data_get(optional($settings->ozopay), 'secret');
        }
        else {
            $tid = settings('ozopay_tid', env('OZOPAY_TID'));
            $sec = settings('ozopay_secret', env('OZOPAY_SECRET'));
        }

        $url = app()->environment('production')
            ? settings('ozopay_url', env('OZOPAY_URL'))
            : settings('ozopay_sandbox_url', env('OZOPAY_SANDBOX_URL'));

        return $tid && $sec && $url ? compact('tid', 'sec', 'url') : null;
    }

    /**
     * Get gkash keys
     */
    public function getGkashKeys($tenant)
    {
        if ($tenant) {
            $settings = $tenant->settings;
            $mid = data_get($settings, 'gkash_mid') ?? data_get(optional($settings->gkash), 'mid');
            $sk = data_get($settings, 'gkash_signature_key') ?? data_get(optional($settings->gkash), 'signature_key');
        }
        else {
            $mid = settings('gkash_mid', env('GKASH_MID'));
            $sk = settings('gkash_signature_key', env('GKASH_SIGNATURE_KEY'));
        }

        $url = app()->environment('production')
            ? settings('gkash_url', env('GKASH_URL'))
            : settings('gkash_sandbox_url', env('GKASH_SANDBOX_URL'));

        return $mid && $sk && $url ? compact('mid', 'sk', 'url') : null;
    }

    /**
     * Get ipay keys
     */
    public function getIpayKeys($tenant)
    {
        if ($tenant) {
            $settings = $tenant->settings;
            $mc = data_get($settings, 'ipay_merchant_code') ?? data_get(optional($settings->ipay), 'merchant_code');
            $mk = data_get($settings, 'ipay_merchant_key') ?? data_get(optional($settings->ipay), 'merchant_key');
        }
        else {
            $mc = settings('ipay_merchant_code', env('IPAY_MERCHANT_CODE'));
            $mk = settings('ipay_merchant_key', env('IPAY_MERCHANT_KEY'));
        }

        $url = settings('ipay_url', env('IPAY_URL'));
        $qurl = settings('ipay_query_url', env('IPAY_QUERY_URL'));
        $title = 'iPay88';

        return $mc && $mc && $url && $qurl 
            ? compact('mc', 'mk', 'url', 'qurl', 'title')
            : null;
        
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.payment-gateway');
    }
}