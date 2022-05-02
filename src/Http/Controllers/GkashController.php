<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class GkashController extends Controller
{
    /**
     * Create request signature
     */
    public function sign()
    {
        $params = request()->input('params');
        $credentials = $this->getCredentials(request()->input('account_id'));
        $metadata = [
            'job' => data_get($params, 'job'), 
            'payment_id' => data_get($params, 'payment_id'),
            'account_id' => data_get($params, 'account_id'),
        ];

        $body = [
            'version' => $credentials->api_version,
            'CID' => $credentials->mid,
            'v_currency' => data_get($params, 'currency'),
            'v_amount' => currency(data_get($params, 'amount')),
            'v_cartid' => data_get($params, 'payment_id'),
            'v_productdesc' => data_get($params, 'payment_description'),
            'preselection' => $this->getPaymentMode(data_get($params, 'payment_mode')),
            'returnurl' => route('__gkash.redirect', $metadata),
            'callbackurl' => route('__gkash.webhook', $metadata),
        ];

        $signature = $this->getSignature($body, $credentials->mid, $credentials->signature_key);

        return response()->json([
            'body' => array_merge($body, ['signature' => $signature]),
            'endpoint' => app()->environment('production') ? $credentials->url : $credentials->sandbox_url,
        ]);
    }

    /**
     * Redirect
     */
    public function redirect()
    {
        $credentials = $this->getCredentials(request()->query('account_id'));
        $this->verifyResponse(request()->all(), $credentials->mid, $credentials->signature_key);

        if ($job = $this->getJob()) {
            return ($job)::dispatchNow([
                'status' => $this->getStatus(request()->input('status')),
                'provider' => 'gkash',
                'pay_response' => request()->all(),
            ]);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        $credentials = $this->getCredentials(request()->query('account_id'));
        $this->verifyResponse(request()->all(), $credentials->mid, $credentials->signature_key);

        if ($job = $this->getJob()) {
            return ($job)::dispatch([
                'status' => $this->getStatus(request()->input('status')),
                'provider' => 'gkash',
                'pay_response' => request()->all(),
            ]);
        }
    }

    /**
     * Get job
     */
    public function getJob()
    {
        $name = request()->query('job') ?? 'Gkash';
        $ns = (object)[
            'try' => 'App\\Jobs\\'.$name.'Provision',
            'default' => 'Jiannius\\Atom\\Jobs\\'.$name.'Provision',
        ];

        if (class_exists($ns->try)) return $ns->try;
        else if (class_exists($ns->default)) return $ns->default;
    }

    /**
     * Get Credentials
     */
    public function getCredentials($accountId = null)
    {
        $account = model('account')->find($accountId);

        return (object)[
            'mid' => $account
                ? ($account->setting->gkash_mid ?? $account->setting->gkash->mid ?? null)
                : site_settings('gkash_mid', env('GKASH_MID')),

            'signature_key' => $account
                ? ($account->setting->gkash_signature_key ?? $account->setting->gkash->signature_key ?? null)
                : site_settings('gkash_signature_key', env('GKASH_SIGNATURE_KEY')),

            'api_version' => $account
                ? ($account->setting->gkash_api_version ?? $account->setting->gkash->api_version ?? null)
                : site_settings('gkash_api_version', env('GKASH_API_VERSION')),

            'url' => $account
                ? ($account->setting->gkash_url ?? $account->setting->gkash->url ?? null)
                : site_settings('gkash_url', env('GKASH_URL')),

            'sandbox_url' => $account
                ? ($account->setting->gkash_sandbox_url ?? $account->setting->gkash->sandbox_url ?? null)
                : site_settings('gkash_sandbox_url', env('GKASH_SANDBOX_URL')),
        ];
    }

    /**
     * Get signature
     */
    public function getSignature($body, $mid, $signatureKey)
    {
        $data = implode(';', [
            $signatureKey,
            $mid,
            data_get($body, 'v_cartid'),
            str(data_get($body, 'v_amount'))->replace('.', '')->replace(',', '')->toString(),
            data_get($body, 'v_currency'),
        ]);

        return hash('sha512', strtoupper($data));
    }

    /**
     * Verify response
     */
    public function verifyResponse($response, $mid, $signatureKey)
    {
        $data = implode(';', [
            $signatureKey,
            $mid,
            data_get($response, 'POID'),
            data_get($response, 'cartid'),
            str(data_get($response, 'amount'))->replace('.', '')->replace(',', '')->toString(),
            data_get($response, 'currency'),
            data_get($response, 'status'),
        ]);

        $signature = hash('sha512', strtoupper($data));

        if ($signature !== data_get($response, 'signature')) abort(400, 'Gkash response signature validation failed.');

        return true;
    }

    /**
     * Get status
     */
    public function getStatus($code)
    {
        return [
            '88 - Transferred' => 'success',
            '66 - Failed' => 'failed',
            '11 - Pending' => 'processing',
            '99 - Error' => 'failed',
        ][$code] ?? 'failed';
    }

    /**
     * Get payment mode
     */
    public function getPaymentMode($mode = null)
    {
        return [
            'fpx' => 'EBANKING',
            'ewallet' => 'EWALLET',
            'credit-card' => 'ECOMM',
        ][$mode] ?? null;
    }
}