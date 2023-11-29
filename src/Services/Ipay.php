<?php

namespace Jiannius\Atom\Services;

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

    // get job handler
    public function getJobHandler() : mixed
    {
        $jobname = request()->query('job') ?? 'IpayProvision';
        $jobhandler = collect([
            'App\\Jobs\\'.$jobname,
            'Jiannius\\Atom\\Jobs\\'.$jobname,
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        return $jobhandler;
    }

    // get signature
    public function getSignature($body) : string
    {
        $data = [
            $this->credentials->get('merchant_key'),
            data_get($body, 'MerchantCode'),
            data_get($body, 'RefNo'),
            str(data_get($body, 'Amount'))->replace('.', '')->replace(',', '')->toString(),
            data_get($body, 'Currency'),
        ];

        $str = implode('', $data);

        return hash('sha256', $str);
    }

    // checkout
    public function checkout($params) : mixed
    {
        $data = [
            'MerchantCode' => $this->credentials->get('merchant_code'),
            'RefNo' => (string) data_get($params, 'payment_id'),
            'Amount' => app()->environment('production')
                ? currency(data_get($params, 'amount'))
                : currency(1),
            'Currency' => data_get($params, 'currency'),
            'ProdDesc' => data_get($params, 'payment_description'),
            'UserName' => data_get($params, 'customer.name'),
            'UserEmail' => data_get($params, 'customer.email'),
            'UserContact' => data_get($params, 'customer.phone'),
            'SignatureType' => 'SHA256',
            'ResponseURL' => route('__ipay.redirect', ['job' => data_get($params, 'job')]),
            'BackendURL' => route('__ipay.webhook', ['job' => data_get($params, 'job')]),
        ];

        $body = array_merge($data, ['signature' => $this->getSignature($data)]);

        return to_route('__ipay.checkout', [
            'body' => $body,
            'url' => $this->credentials->get('url'),
        ]);
    }
}