<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Facades\Http;

class Senangpay
{
    public $credentials;

    // constructor
    public function __construct($credentials = [])
    {
        $this->credentials = collect($credentials ?: [
            'merchant_id' => settings('senangpay_merchant_id'),
            'secret_key' => settings('senangpay_secret_key'),
        ]);
    }

    // endpoint
    public function endpoint($uri) : string
    {
        $endpoint = app()->environment('production')
            ? 'https://app.senangpay.my'
            : 'https://sandbox.senangpay.my';

        $api = str($uri)->is('payment/*') ? '/' : '/apiv1//';

        return $endpoint.$api.$uri;
    }

    // test
    public function test() : array
    {
        try {
            $mid = get($this->credentials, 'merchant_id');
            $sk = get($this->credentials, 'secret_key');
    
            $response = Http::get($this->endpoint('query_order_status'), [
                'merchant_id' => $mid,
                'order_id' => 'testing-123',
                'hash' => hash_hmac('sha256', collect([$mid, $sk, 'testing-123'])->join(''), $sk),
            ]);

            throw_if(!get($response, 'status'), \Exception::class, get($response, 'msg'));

            return [
                'success' => true,
                'error' => null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection failed',
            ];
        }
    }

    // query order status
    public function queryOrderStatus($orderId) : mixed
    {
        $mid = get($this->credentials, 'merchant_id');
        $sk = get($this->credentials, 'secret_key');

        $response = Http::get($this->endpoint('query_order_status'), [
            'merchant_id' => $mid,
            'order_id' => $orderId,
            'hash' => hash_hmac('sha256', collect([$mid, $sk, $orderId])->join(''), $sk),
        ]);

        return get($response, 'data.0');
    }

    // checkout
    public function checkout($params) : mixed
    {
        $mid = get($this->credentials, 'merchant_id');
        $sk = get($this->credentials, 'secret_key');
        $detail = get($params, 'detail');
        $amount = (string) str(format(get($params, 'amount'))->value())->replace(',', '');
        $orderId = get($params, 'order_id');

        $params = [
            ...$params,
            'amount' => $amount,
            'hash' => hash_hmac('sha256', collect([$sk, $detail, $amount, $orderId])->join(''), $sk),
        ];

        $url = $this->endpoint('payment/'.$mid).'?'.http_build_query($params);

        return redirect($url);
    }

    // parse payload
    public function parsePayload($payload) : mixed
    {
        $sk = get($this->credentials, 'secret_key');
        $status = get($payload, 'status_id');
        $order = get($payload, 'order_id');
        $transaction = get($payload, 'transaction_id');
        $msg = get($payload, 'msg');
        $hash = hash_hmac('sha256', collect([$sk.$status.$order.$transaction.$msg])->join(''), $sk);

        if ($hash !== get($payload, 'hash')) {
            logger('Unable to validate hashing.');
            return false;
        }

        return [
            ...$payload,
            'status' => $this->parseStatus($payload),
        ];
    }

    // parse status
    public function parseStatus($payload) : mixed
    {
        $status = get($payload, 'status_id') ?? get($payload, 'payment_info.status');

        return pick([
            'failed' => in_array($status, ['0', 'failed']),
            'success' => in_array($status, ['1', 'paid']),
            'pending' => $status === '2',
        ]);
    }
}