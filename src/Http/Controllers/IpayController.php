<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class IpayController extends Controller
{
    /**
     * Create request signature
     */
    public function sign()
    {
        $params = request()->input('params');
        $account = model('account')->find(request()->input('account_id'));
        $credentials = $this->getCredentials($account);

        $amount = app()->environment('production')
            ? currency(data_get($params, 'amount'))
            : currency(1);

        $body = [
            'MerchantCode' => $credentials->merchant_code,
            'RefNo' => (string)data_get($params, 'payment_id'),
            'Amount' => $amount,
            'Currency' => data_get($params, 'currency'),
            'ProdDesc' => data_get($params, 'payment_description'),
            'UserName' => data_get($params, 'name'),
            'UserEmail' => data_get($params, 'email'),
            'UserContact' => data_get($params, 'phone'),
            'SignatureType' => 'SHA256',
            'ResponseURL' => route('__ipay.redirect', ['job' => data_get($params, 'job')]),
            'BackendURL' => route('__ipay.webhook', ['job' => data_get($params, 'job')]),
        ];

        $signature = $this->getSignature($body, $credentials->merchant_key);

        return response()->json([
            'body' => array_merge($body, ['Signature' => $signature]),
            'endpoint' => $credentials->url,
        ]);
    }

    /**
     * Redirect
     */
    public function redirect()
    {
        if ($job = $this->getJob()) {
            return ($job)::dispatchNow($this->getStatus(), request()->all());
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
     * Get Credentials
     */
    public function getCredentials($account = null)
    {
        return (object)[
            'merchant_code' => $account
                ? ($account->setting->ipay_merchant_code ?? $account->setting->ipay->merchant_code ?? null)
                : site_settings('ipay_merchant_code', env('IPAY_MERCHANT_CODE')),

            'merchant_key' => $account
                ? ($account->setting->ipay_merchant_key ?? $account->setting->ipay->merchant_key ?? null)
                : site_settings('ipay_merchant_key', env('IPAY_MERCHANT_KEY')),

            'url' => $account
                ? ($account->setting->ipay_url ?? $account->setting->ipay->url ?? null)
                : site_settings('ipay_url', env('IPAY_URL')),

            'query_url' => $account
                ? ($account->setting->ipay_query_url ?? $account->setting->ipay->query_url ?? null)
                : site_settings('ipay_query_url', env('IPAY_QUERY_URL')),
        ];
    }

    /**
     * Get signature
     */
    public function getSignature($body, $key)
    {
        $amount = str(data_get($body, 'Amount'))
            ->replace('.', '')
            ->replace(',', '')
            ->toString();

        $data = [
            $key,
            data_get($body, 'MerchantCode'),
            data_get($body, 'RefNo'),
            $amount,
            data_get($body, 'Currency'),
        ];

        $str = implode('', $data);

        return hash('sha256', $str);
    }

    /**
     * Get status
     */
    public function getStatus()
    {
        $status = request()->input('Status');

        return $status === '1' ? 'success' : 'failed';
    }
}