<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;

class OzopayController extends Controller
{
    /**
     * Checkout
     */
    public function checkout()
    {
        $params = session('pay_request');
        $keys = $this->getOzopayKeys(data_get($params, 'account_id'));

        // $metadata = [
        //     'job' => data_get($params, 'job'),
        //     'payment_id' => data_get($params, 'payment_id'),
        //     'account_id' => data_get($params, 'account_id'),
        // ];

        $data = [
            'address' => data_get($params, 'customer.address'),
            'city' => data_get($params, 'customer.city'),
            'country' => data_get($params, 'customer.country'),
            'currencyText' => data_get($params, 'currency'),
            'customerPaymentPageText' => data_get($keys, 'tid'),
            'email' => data_get($params, 'customer.email'),
            'firstName' => data_get($params, 'customer.first_name'),
            'issameasbilling' => '1',
            'lastName' => data_get($params, 'customer.last_name'),
            'orderDescription' => implode(':', array_filter([
                data_get($params, 'job'),
                data_get($params, 'payment_id'),
                str()->random(6),
            ])),
            'orderDetail' => data_get($params, 'payment_description'),
            'phone' => data_get($params, 'customer.phone'),
            'purchaseAmount' => currency(data_get($params, 'amount', 0)),
            'shipAddress' => null,
            'shipCity' => null,
            'shipCountry' => null,
            'shipFirstName' => null,
            'shipLastName' => null,
            'shipState' => null,
            'shipZip' => null,
            'state' => data_get($params, 'customer.state'),
            'transactionOriginatedURL' => route('__ozopay.redirect'),
            'zip' => data_get($params, 'customer.postcode'),
        ];

        $body = array_merge($data, [
            'signature' => $this->getOzopaySignature($data, $keys),
        ]);

        return $this->getOzopayCheckoutForm($body, data_get($keys, 'url'));
    }

    /**
     * Redirect
     */
    public function redirect()
    {
        $response = request()->all();
        $status = $this->getStatus($response);

        if ($jobhandler = $this->getJobHandler()) {
            return ($jobhandler)::dispatchNow([
                'provider' => 'ozopay',
                'status' => $status, 
                'response' => $response,
            ]);
        }
    }

    /**
     * Webhook
     */
    public function webhook()
    {
        $response = request()->all();
        $status = $this->getStatus($response);

        if ($jobhandler = $this->getJobHandler()) {
            ($jobhandler)::dispatch([
                'provider' => 'ozopay',
                'status' => $status, 
                'response' => $response, 
                'webhook' => true,
            ]);
        }
    }

    /**
     * Get job handler
     */
    public function getJobHandler()
    {
        $jobname = request()->query('job') ?? 'OzopayProvision';
        $jobhandler = collect([
            'App\\Jobs\\'.$jobname,
            'Jiannius\\Atom\\Jobs\\'.$jobname,
        ])->first(fn($ns) => class_exists($ns));

        return $jobhandler;
    }

    /**
     * Get ozopay checkout form
     */
    public function getOzopayCheckoutForm($body, $url)
    {
        $form = '';

        foreach (array_keys($body) as $key) {
            $form .= '<input name="'.$key.'" value="'.data_get($body, $key).'">';
        }

        return Blade::render(<<<EOL
            <form name="ozopay_checkout" method="POST" action="$url" style="display: none;">
                $form
            </form>

            <div>Redirecting to payment gateway...</div>

            <script>
                window.onload = function() {
                    document.forms['ozopay_checkout'].submit()
                }
            </script>
        EOL
        );
    }

    /**
     * Get ozopay keys
     */
    public function getOzopayKeys($accountId = null)
    {
        $account = $accountId ? model('account')->find($accountId) : null;
        $settings = optional($account)->settings;

        $tid = $account
            ? data_get($settings, 'ozopay_tid') ?? data_get(optional($settings->ozopay), 'tid')
            : site_settings('ozopay_tid', env('OZOPAY_TID'));

        $secret = $account
            ? data_get($settings, 'ozopay_secret') ?? data_get(optional($settings->ozopay), 'secret')
            : site_settings('ozopay_secret', env('OZOPAY_SECRET'));

        if (app()->environment('production')) {
            $url = $account
                ? data_get($settings, 'ozopay_url') ?? data_get(optional($settings->ozopay), 'url')
                : site_settings('ozopay_url', env('OZOPAY_URL'));
        }
        else {
            $url = $account
                ? data_get($settings, 'ozopay_sandbox_url') ?? data_get(optional($settings->ozopay), 'sandbox_url')
                : site_settings('ozopay_sandbox_url', env('OZOPAY_SANDBOX_URL'));
        }

        return compact('tid', 'secret', 'url');
    }

    /**
     * Get ozopay signature
     */
    public function getOzopaySignature($data, $keys)
    {
        $concat = collect(array_values($data))
            ->push(data_get($keys, 'secret'))
            ->join('');

        $signature = hash('sha256', $concat);

        return $signature;
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