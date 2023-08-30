<?php

namespace Jiannius\Atom\Services;

use Exception;
use RevenueMonster\SDK\RevenueMonster as RevenueMonsterSdk;
use RevenueMonster\SDK\Exceptions\ApiException;
use RevenueMonster\SDK\Exceptions\ValidationException;
use RevenueMonster\SDK\Request\WebPayment;
use RevenueMonster\SDK\Request\QRPay;
use RevenueMonster\SDK\Request\QuickPay;

class RevenueMonster
{
    public $rm;
    public $storeId;

    // constructor
    public function __construct($settings = null)
    {
        $this->rm = new RevenueMonsterSdk([
            'clientId' => data_get($settings, 'revenue_monster_client_id', settings('revenue_monster_client_id')),
            'clientSecret' => data_get($settings, 'revenue_monster_client_secret', settings('revenue_monster_client_secret')),
            'privateKey' => file_get_contents(storage_path('app/rm-private-key.pem')),
            'isSandbox' => (bool) data_get($settings, 'revenue_monster_is_sandbox', settings('revenue_monster_is_sandbox')),
        ]);

        $this->storeId = data_get($settings, 'revenue_monster_store_id', settings('revenue_monster_store_id'));
    }

    // get merchant profile
    public function getMerchantProfile() : mixed
    {
        return $this->rm->merchant->profile();
    }

    // get merchant subscriptions
    public function getMerchantSubscriptions() : mixed
    {
        return $this->rm->merchant->subscriptions();
    }

    // get merchant stores
    public function getMerchantStores($paginate = 30) : mixed
    {
        return $this->rm->store->paginate($paginate);
    }

    // get order
    public function getOrder($id) : mixed
    {
        return $this->rm->payment->findByOrderId($id);
    }

    // get transaction
    public function getTransaction($id) : mixed
    {
        return $this->rm->payment->find($id);
    }

    // create web payment
    public function createWebPayment($req) : mixed
    {
        try {
            $wp = new WebPayment;
            $wp->order->id = data_get($req, 'id');
            $wp->order->title = data_get($req, 'title');
            $wp->order->currencyType = data_get($req, 'currencyType');
            $wp->order->amount = data_get($req, 'amount');
            $wp->order->detail = data_get($req, 'detail');
            $wp->order->additionalData = data_get($req, 'additionalData');
            $wp->storeId = data_get($req, 'storeId', $this->storeId);
            $wp->redirectUrl = data_get($req, 'redirectUrl', route('__revenue-monster.redirect'));
            $wp->notifyUrl = data_get($req, 'notifyUrl', route('__revenue-monster.webhook'));
            $wp->layoutVersion = 'v3';
    
            return $this->rm->payment->createWebPayment($wp);
        } catch(ApiException $e) {
            dd("statusCode : {$e->getCode()}, errorCode : {$e->getErrorCode()}, errorMessage : {$e->getMessage()}");
        } catch(ValidationException $e) {
            dd($e->getMessage());
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }

    // create qr pay
    public function createQRPay() : mixed
    {
        try {
            $qrPay = new QRPay();
            $qrPay->currencyType = 'MYR';
            $qrPay->amount = 100;
            $qrPay->isPreFillAmount = true;
            $qrPay->order->title = '服务费';
            $qrPay->order->detail = 'testing';
            $qrPay->method = [];
            $qrPay->redirectUrl = 'https://shop.v1.mamic.asia/app/index.php?i=6&c=entry&m=ewei_shopv2&do=mobile&r=order.pay_rmwxpay.complete&openid=ot3NT0dxs4A8h4sVZm-p7q_MUTtQ&fromwechat=1';
            $qrPay->storeId = '1553067342153519097';
            $qrPay->type = 'DYNAMIC';

            return $this->rm->payment->qrPay($qrPay);
        } catch(ApiException $e) {
            dd("statusCode : {$e->getCode()}, errorCode : {$e->getErrorCode()}, errorMessage : {$e->getMessage()}");
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }

    // create quick pay
    public function createQuickPay() : mixed
    {
        try {
            $qp = new QuickPay;
            $qp->authCode = '281011026026517778602435';
            $qp->order->id = '443';
            $qp->order->title = '【原味系列】 猫山王榴';
            $qp->order->currencyType = 'MYR';
            $qp->order->amount = 10;
            $qp->order->detail = '';
            $qp->order->additionalData = 'SH20190819100656262762';
            $qp->ipAddress = '8.8.8.8';
            $qp->storeId = "1553067342153519097";
        
            return $this->rm->payment->quickPay($qp);
        } catch(ApiException $e) {
            dd("statusCode : {$e->getCode()}, errorCode : {$e->getErrorCode()}, errorMessage : {$e->getMessage()}");
        } catch(ValidationException $e) {
            dd($e->getMessage());
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }
}