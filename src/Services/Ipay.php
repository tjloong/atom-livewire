<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Facades\Blade;

class Ipay
{
    public $credentials;

    // constructor
    public function __construct()
    {
        $this->setCredentials();
    }

    // set credentials
    public function setCredentials($credentials = null) : void
    {
        $this->credentials = collect($credentials ?? [
            'merchant_code' => settings('ipay_merchant_code'),
            'merchant_key' => settings('ipay_merchant_key'),
            'url' => settings('ipay_url'),
            'query_url' => settings('ipay_query_url'),
        ]);
    }

    // get signature
    public function getSignature($body) : string
    {
        $data = [
            $this->credentials->get('merchant_key'),
            get($body, 'MerchantCode'),
            get($body, 'RefNo'),
            str(get($body, 'Amount'))->replace('.', '')->replace(',', '')->toString(),
            get($body, 'Currency'),
        ];

        $str = implode('', $data);

        return hash('sha256', $str);
    }

    // checkout
    public function checkout($params) : mixed
    {
        $data = [
            ...$params,
            'Amount' => app()->environment('production') ? currency(get($params, 'Amount')) : currency(1),
            'MerchantCode' => $this->credentials->get('merchant_code'),
            'SignatureType' => 'SHA256',
            'ResponseURL' => route('__ipay.redirect'),
            'BackendURL' => route('__ipay.webhook'),
        ];

        $body = [
            ...$data,
            'signature' => $this->getSignature($data),
        ];

        return to_route('__ipay.checkout', [
            'body' => $body,
            'url' => $this->credentials->get('url'),
        ]);
    }

    // get checkout form
    public static function getCheckoutForm() : mixed
    {
        $url = request()->url;
        $body = request()->body;
        $form = '';

        foreach (array_keys($body) as $key) {
            $form .= '<input name="'.$key.'" value="'.get($body, $key).'">';
        }

        $response = <<<EOL
        <form name="ipay_checkout" method="POST" action="$url" style="display: none;">$form</form>
        <div>Redirecting to payment gateway...</div>
        <script>window.onload = function() { document.forms['ipay_checkout'].submit() }</script>
        EOL;

        return Blade::render($response);
    }
}