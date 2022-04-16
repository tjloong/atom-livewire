<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Jobs\OzopayFulfillment;
use App\Http\Controllers\Controller;

class OzopayController extends Controller
{
    /**
     * Create request signature
     */
    public function sign()
    {
        $params = request()->input('params');
        $account = model('account')->find(request()->input('account_id'));
        $tid = $this->getTID($account);
        $secret = $this->getSecret($account);
        $endpoint = $this->getEndpoint($account);

        $body = [
            'address' => $params['address'] ?? null,
            'city' => $params['city'] ?? null,
            'country' => $params['country'] ?? null,
            'currencyText' => $params['currency'] ?? null,
            'customerPaymentPageText' => $tid,
            'email' => $params['email'] ?? null,
            'firstName' => $params['first_name'] ?? null,
            'issameasbilling' => '1',
            'lastName' => $params['last_name'] ?? null,
            'orderDescription' => $params['order_id'] ?? null,
            'orderDetail' => $params['order_description'] ?? null,
            'phone' => $params['phone'] ?? null,
            'purchaseAmount' => currency($params['amount'] ?? 0),
            'shipAddress' => null,
            'shipCity' => null,
            'shipCountry' => null,
            'shipFirstName' => null,
            'shipLastName' => null,
            'shipState' => null,
            'shipZip' => null,
            'state' => $params['state'] ?? null,
            'transactionOriginatedURL' => $params['redirect_url'] ?? route('__ozopay.redirect'),
            'zip' => $params['postcode'] ?? null,
        ];

        $concat = collect(array_values($body))->push($secret)->join('');
        $signature = hash('sha256', $concat);

        return response()->json([
            'body' => array_merge($body, compact('signature')),
            'endpoint' => $endpoint,
        ]);
    }

    /**
     * Redirect
     */
    public function redirect()
    {
        $type = 'redirect';
        $response = request()->all();
        $status = $this->getStatus($response);

        return OzopayFulfillment::dispatchNow((object)compact('type', 'status', 'response'));
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        $type = 'webhook';
        $response = request()->all();
        $status = $this->getStatus($response);

        OzopayFulfillment::dispatch((object)compact('type', 'status', 'response'));
    }

    /**
     * Get TID
     */
    private function getTID($account = null)
    {
        if ($account) return $account->setting->ozopay_tid ?? $account->setting->ozopay->tid ?? null;
        else return site_settings('ozopay_tid', env('OZOPAY_TID'));
    }

    /**
     * Get secret
     */
    private function getSecret($account = null)
    {
        if ($account) return $account->setting->ozopay_secret ?? $account->setting->ozopay->secret ?? null;
        else return site_settings('ozopay_secret', env('OZOPAY_SECRET'));
    }

    /**
     * Get endpoint
     */
    private function getEndpoint($account = null)
    {
        if ($account) {
            if (app()->environment('production')) return $account->setting->ozopay_url ?? $account->setting->ozopay->url ?? null;
            else return $account->setting->ozopay_sandbox_url ?? $account->setting->ozopay->sandbox_url ?? null;
        }
        else if (app()->environment('production')) return site_settings('ozopay_url', env('OZOPAY_URL'));
        else return site_settings('ozopay_sandbox_url', env('OZOPAY_SANDBOX_URL'));
    }

    /**
     * Get status
     */
    private function getStatus($response)
    {
        $message = $this->getErrorMessage($response['ResponseCode']);
        $status = in_array($message, ['Success', 'Failed', 'Pending', 'Cancelled'])
            ? strtolower($message)
            : 'failed';

        return $status;
    }

    /**
     * Get message
     */
    private function getErrorMessage($code)
    {
        $path = __DIR__.'/../../../resources/json/ozopay.json';
        $json = json_decode(file_get_contents($path));

        return data_get($json->errors, $code);
    }
}