<?php

namespace Jiannius\Atom\Http\Controllers;

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
        $credentials = $this->getCredentials($account);
        $identifier = implode(':', array_filter([
            data_get($params, 'job'),
            data_get($params, 'payment_id'),
            str()->random(6),
        ]));

        $body = [
            'address' => data_get($params, 'address'),
            'city' => data_get($params, 'city'),
            'country' => data_get($params, 'country'),
            'currencyText' => data_get($params, 'currency'),
            'customerPaymentPageText' => $credentials->tid,
            'email' => data_get($params, 'email'),
            'firstName' => data_get($params, 'first_name'),
            'issameasbilling' => '1',
            'lastName' => data_get($params, 'last_name'),
            'orderDescription' => $identifier,
            'orderDetail' => data_get($params, 'payment_description'),
            'phone' => data_get($params, 'phone'),
            'purchaseAmount' => currency(data_get($params, 'amount', 0)),
            'shipAddress' => null,
            'shipCity' => null,
            'shipCountry' => null,
            'shipFirstName' => null,
            'shipLastName' => null,
            'shipState' => null,
            'shipZip' => null,
            'state' => data_get($params, 'state'),
            'transactionOriginatedURL' => route('__ozopay.redirect'),
            'zip' => data_get($params, 'postcode'),
        ];

        $concat = collect(array_values($body))->push($credentials->secret)->join('');
        $signature = hash('sha256', $concat);

        return response()->json([
            'body' => array_merge($body, compact('signature')),
            'endpoint' => app()->environment('production') ? $credentials->url : $credentials->sandbox,
        ]);
    }

    /**
     * Redirect
     */
    public function redirect()
    {
        $response = request()->all();
        $status = $this->getStatus($response);

        if ($job = $this->getJob()) {
            return ($job)::dispatchNow($status, $response);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        $response = request()->all();
        $status = $this->getStatus($response);

        if ($job = $this->getJob()) ($job)::dispatch($status, $response);
    }

    /**
     * Get job
     */
    public function getJob()
    {
        $name = request()->query('job') ?? 'Ozopay';
        $ns = (object)[
            'try' => 'App\\Jobs\\'.$name.'Provision',
            'default' => 'Jiannius\\Atom\\Jobs\\'.$name.'Provision',
        ];

        if (class_exists($ns->try)) return $ns->try;
        else if (class_exists($ns->default)) return $ns->default;
    }

    /**
     * Get keys
     */
    public function getCredentials($account = null)
    {
        return (object)[
            'tid' => $account
                ? ($account->setting->ozopay_tid ?? $account->setting->ozopay->tid ?? null)
                : site_settings('ozopay_tid', env('OZOPAY_TID')),

            'secret' => $account
                ? ($account->setting->ozopay_secret ?? $account->setting->ozopay->secret ?? null)
                : site_settings('ozopay_secret', env('OZOPAY_SECRET')),

            'url' => $account
                ? ($account->setting->ozopay_url ?? $account->setting->ozopay->url ?? null)
                : site_settings('ozopay_url', env('OZOPAY_URL')),

            'sandbox' => $account
                ? ($account->setting->ozopay_sandbox_url ?? $account->setting->ozopay->sandbox_url ?? null)
                : site_settings('ozopay_sandbox_url', env('OZOPAY_SANDBOX_URL')),
        ];
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