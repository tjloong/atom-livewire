<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;

class GkashController extends Controller
{
    /**
     * Checkout
     */
    public function checkout()
    {
        $params = session('pay_request');
        $keys = $this->getGkashKeys(data_get($params, 'account_id'));
        
        $metadata = [
            'job' => data_get($params, 'job'), 
            'payment_id' => data_get($params, 'payment_id'),
            'account_id' => data_get($params, 'account_id'),
        ];

        $paymode = [
            'fpx' => 'EBANKING',
            'ewallet' => 'EWALLET',
            'credit-card' => 'ECOMM',
        ][data_get($params, 'payment_mode')] ?? null;

        $data = [
            'version' => data_get($keys, 'version'),
            'CID' => data_get($keys, 'mid'),
            'v_currency' => data_get($params, 'currency'),
            'v_amount' => currency(data_get($params, 'amount')),
            'v_cartid' => data_get($params, 'payment_id'),
            'v_productdesc' => data_get($params, 'payment_description'),
            'preselection' => $paymode,
            'returnurl' => route('__gkash.redirect', $metadata),
            'callbackurl' => route('__gkash.webhook', $metadata),
        ];

        $body = array_merge($data, [
            'signature' => $this->getGkashSignature($data, $keys),
        ]);

        return $this->getGkashCheckoutForm($body, data_get($keys, 'url'));
    }

    /**
     * Redirect
     */
    public function redirect()
    {
        $params = request()->query();
        $keys = $this->getGkashKeys(data_get($params, 'account_id'));
        $this->verifyGkashResponse(request()->all(), $keys);

        if ($jobhandler = $this->getJobHandler()) {
            return ($jobhandler)::dispatchNow([
                'status' => $this->getStatus(request()->input('status')),
                'provider' => 'gkash',
                'response' => request()->all(),
            ]);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        $params = request()->query();
        $keys = $this->getGkashKeys(data_get($params, 'account_id'));
        $this->verifyGkashResponse(request()->all(), $keys);

        if ($jobhandler = $this->getJobHandler()) {
            return ($jobhandler)::dispatch([
                'status' => $this->getStatus(request()->input('status')),
                'provider' => 'gkash',
                'pay_response' => request()->all(),
            ]);
        }
    }

    /**
     * Get job handler
     */
    public function getJobHandler()
    {
        $jobname = request()->query('job') ?? 'GkashProvision';
        $jobhandler = collect([
            'App\\Jobs\\'.$jobname,
            'Jiannius\\Atom\\Jobs\\'.$jobname,
        ])->first(fn($ns) => class_exists($ns));

        return $jobhandler;
    }

    /**
     * Get gkash checkout form
     */
    public function getGkashCheckoutForm($body, $url)
    {
        $form = '';

        foreach (array_keys($body) as $key) {
            $form .= '<input name="'.$key.'" value="'.data_get($body, $key).'">';
        }

        return Blade::render(<<<EOL
            <form name="gkash_checkout" method="POST" action="$url" style="display: none;">
                $form
            </form>

            <div>Redirecting to payment gateway...</div>

            <script>
                window.onload = function() {
                    document.forms['gkash_checkout'].submit()
                }
            </script>
        EOL
        );
    }

    /**
     * Get gkash keys
     */
    public function getGkashKeys($accountId = null)
    {
        $account = $accountId ? model('account')->find($accountId) : null;
        $settings = optional($account)->settings;

        $mid = $account
            ? data_get($settings, 'gkash_mid') ?? data_get(optional($settings->gkash), 'mid')
            : site_settings('gkash_mid', env('GKASH_MID'));

        $sk = $account
            ? data_get($settings, 'gkash_signature_key') ?? data_get(optional($settings->gkash), 'signature_key')
            : site_settings('gkash_signature_key', env('GKASH_SIGNATURE_KEY'));

        $version = $account
            ? data_get($settings, 'gkash_api_version') ?? data_get(optional($settings->gkash), 'api_version')
            : site_settings('gkash_api_version', env('GKASH_API_VERSION'));

        if (app()->environment('production')) {
            $url = $account
                ? data_get($settings, 'gkash_url') ?? data_get(optional($settings->gkash), 'url')
                : site_settings('gkash_url', env('GKASH_URL'));
        }
        else {
            $url = $account
                ? data_get($settings, 'gkash_sandbox_url') ?? data_get(optional($settings->gkash), 'sandbox_url')
                : site_settings('gkash_sandbox_url', env('GKASH_SANDBOX_URL'));
        }

        return compact('mid', 'sk', 'version', 'url');
    }

    /**
     * Get gkash signature
     */
    public function getGkashSignature($body, $keys)
    {
        $mid = data_get($keys, 'mid');
        $sk = data_get($keys, 'sk');
        $data = implode(';', [
            $sk,
            $mid,
            data_get($body, 'v_cartid'),
            str(data_get($body, 'v_amount'))->replace('.', '')->replace(',', '')->toString(),
            data_get($body, 'v_currency'),
        ]);

        return hash('sha512', strtoupper($data));
    }

    /**
     * Verify gkash response
     */
    public function verifyGkashResponse($response, $keys)
    {
        $sk = data_get($keys, 'sk');
        $mid = data_get($keys, 'mid');
        $data = implode(';', [
            $sk,
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
}