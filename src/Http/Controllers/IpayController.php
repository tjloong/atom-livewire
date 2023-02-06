<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;

class IpayController extends Controller
{
    /**
     * Checkout
     */
    public function checkout()
    {
        $params = session('pay_request');
        $keys = $this->getIpayKeys(data_get($params, 'tenant_id'));

        $amount = app()->environment('production')
            ? currency(data_get($params, 'amount'))
            : currency(1);

        $data = [
            'MerchantCode' => data_get($keys, 'mc'),
            'RefNo' => (string)data_get($params, 'payment_id'),
            'Amount' => $amount,
            'Currency' => data_get($params, 'currency'),
            'ProdDesc' => data_get($params, 'payment_description'),
            'UserName' => data_get($params, 'customer.name'),
            'UserEmail' => data_get($params, 'customer.email'),
            'UserContact' => data_get($params, 'customer.phone'),
            'SignatureType' => 'SHA256',
            'ResponseURL' => route('__ipay.redirect', ['job' => data_get($params, 'job')]),
            'BackendURL' => route('__ipay.webhook', ['job' => data_get($params, 'job')]),
        ];

        $body = array_merge($data, [
            'signature' => $this->getIpaySignature($data, $keys),
        ]);

        return $this->getIpayCheckoutForm($body, data_get($keys, 'url'));
    }

    /**
     * Redirect
     */
    public function redirect()
    {
        if ($jobhandler = $this->getJobHandler()) {
            return ($jobhandler)::dispatchNow([
                'provider' => 'ipay',
                'status' => $this->getStatus(), 
                'response' => request()->all(),
            ]);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        if ($jobhandler = $this->getJobHandler()) {
            return ($jobhandler)::dispatch([
                'provider' => 'ipay',
                'status' => $this->getStatus(), 
                'response' => request()->all(), 
                'webhook' => true,
            ]);
        }
    }

    /**
     * Get job handler
     */
    public function getJobHandler()
    {
        $jobname = request()->query('job') ?? 'IpayProvision';
        $jobhandler = collect([
            'App\\Jobs\\'.$jobname,
            'Jiannius\\Atom\\Jobs\\'.$jobname,
        ])->first(fn($ns) => class_exists($ns));

        return $jobhandler;
    }

    /**
     * Get status
     */
    public function getStatus()
    {
        $status = request()->input('Status');

        return $status === '1' ? 'success' : 'failed';
    }

    /**
     * Get ipay checkout form
     */
    public function getIpayCheckoutForm($body, $url)
    {
        $form = '';

        foreach (array_keys($body) as $key) {
            $form .= '<input name="'.$key.'" value="'.data_get($body, $key).'">';
        }

        return Blade::render(<<<EOL
            <form name="ipay_checkout" method="POST" action="$url" style="display: none;">
                $form
            </form>

            <div>Redirecting to payment gateway...</div>

            <script>
                window.onload = function() {
                    document.forms['ipay_checkout'].submit()
                }
            </script>
        EOL
        );
    }

    /**
     * Get ipay keys
     */
    public function getIpayKeys($tenantId = null)
    {
        $tenant = $tenantId ? model('tenant')->find($tenantId) : null;
        $settings = optional($tenant)->settings;

        $mc = $tenant
            ? data_get($settings, 'ipay_merchant_code') ?? data_get(optional($settings->ipay), 'merchant_code')
            : site_settings('ipay_merchant_code', env('IPAY_MERCHANT_CODE'));

        $mk = $tenant
            ? data_get($settings, 'ipay_merchant_key') ?? data_get(optional($settings->ipay), 'merchant_key')
            : site_settings('ipay_merchant_key', env('IPAY_MERCHANT_KEY'));

        $url = $tenant
            ? data_get($settings, 'ipay_url') ?? data_get(optional($settings->ipay), 'url')
            : site_settings('ipay_url', env('IPAY_URL'));

        $qurl = $tenant
            ? data_get($settings, 'ipay_query_url') ?? data_get(optional($settings->ipay), 'query_url')
            : site_settings('ipay_query_url', env('IPAY_QUERY_URL'));

        return compact('mc', 'mk', 'url', 'qurl');
    }

    /**
     * Get ipay signature
     */
    public function getIpaySignature($body, $keys)
    {
        $mk = data_get($keys, 'mk');

        $amount = str(data_get($body, 'Amount'))
            ->replace('.', '')
            ->replace(',', '')
            ->toString();

        $data = [
            $mk,
            data_get($body, 'MerchantCode'),
            data_get($body, 'RefNo'),
            $amount,
            data_get($body, 'Currency'),
        ];

        $str = implode('', $data);

        return hash('sha256', $str);
    }
}