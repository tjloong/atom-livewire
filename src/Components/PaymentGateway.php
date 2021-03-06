<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PaymentGateway extends Component
{
    public $value;
    public $providers;

    public $logos = [
        'ozopay' => ['ozopay', 'fpx', 'visa', 'master', 'tng'],
        'gkash' => ['fpx', 'tng'],
        'stripe' => ['visa', 'master'],
        'ipay' => ['ipay'],
    ];

    public $titles = [
        'ipay' => 'iPay88',
    ];

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $value = [],
        $account = null,
    ) {
        $this->value = $value;
        $this->providers = $this->getProviders($account);
    }

    /**
     * Get providers
     */
    public function getProviders($account = null)
    {
        return collect(config('atom.payment_gateway'))->filter(function($provider) use ($account) {
            if ($provider === 'ozopay') {
                return (
                    (app()->environment('production') && site_settings('ozopay_url', env('OZOPAY_URL')))
                    || (!app()->environment('production') && site_settings('ozopay_sandbox_url', env('OZOPAY_SANDBOX_URL')))
                ) && (
                    (
                        $account 
                        && ($account->setting->ozopay_tid ?? $account->setting->ozopay->tid)
                        && ($account->setting->ozopay_secret ?? $account->setting->ozopay->secret)
                    )
                    || (site_settings('ozopay_tid', env('OZOPAY_TID')) && site_settings('ozopay_secret', env('OZOPAY_SECRET')))
                );
            }
            else if ($provider === 'gkash') {
                return (
                    (app()->environment('production') && site_settings('gkash_url', env('GKASH_URL')))
                    || (!app()->environment('production') && site_settings('gkash_sandbox_url', env('GKASH_SANDBOX_URL')))
                ) && (
                    (
                        $account 
                        && ($account->setting->gkash_mid ?? $account->setting->gkash->mid)
                        && ($account->setting->gkash_signature_key ?? $account->setting->gkash->signature_key)
                    )
                    || (site_settings('gkash_mid', env('GKASH_MID')) && site_settings('gkash_signature_key', env('GKASH_SIGNATURE_KEY')))
                );
            }
            else if ($provider === 'stripe') {
                return (
                    $account
                    && ($account->setting->stripe_public_key ?? $account->setting->stripe->public_key)
                    && ($account->setting->stripe_secret_key ?? $account->setting->stripe->secret_key)
                    && ($account->setting->stripe_webhook_signing_secret ?? $account->setting->stripe->webhook_signing_secret)
                ) || (
                    site_settings('stripe_public_key', env('STRIPE_PUBLIC_KEY'))
                    && site_settings('stripe_secret_key', env('STRIPE_SECRET_KEY'))
                    && site_settings('stripe_webhook_signing_secret', env('STRIPE_WEBHOOK_SIGNING_SECRET'))
                );
            }
            else if ($provider === 'ipay') {
                return (
                    $account
                    && ($account->setting->ipay_merchant_code ?? $account->setting->ipay->merchant_code)
                    && ($account->setting->ipay_merchant_key ?? $account->setting->ipay->merchant_key)
                    && ($account->setting->ipay_url ?? $account->setting->ipay->url)
                    && ($account->setting->ipay_query_url ?? $account->setting->ipay->query_url)
                ) || (
                    site_settings('ipay_merchant_code', env('IPAY_MERCHANT_CODE'))
                    && site_settings('ipay_merchant_key', env('IPAY_MERCHANT_KEY'))
                    && site_settings('ipay_url', env('IPAY_URL'))
                    && site_settings('ipay_query_url', env('IPAY_QUERY_URL'))
                );
            }

            return false;
        });
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.payment-gateway');
    }
}