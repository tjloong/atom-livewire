<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PaymentGateway extends Component
{
    public $form;
    public $value;
    public $providers;

    public $logos = [
        'ozopay' => ['ozopay', 'fpx', 'visa', 'master', 'tng'],
        'gkash' => ['fpx', 'tng'],
    ];

    public $titles = [
        'ipay88' => 'iPay88',
    ];

    public $endpoints = [
        'ozopay' => [
            'local' => 'https://uatpayment.ozopay.com/PaymentEntry/PaymentOption',
            'staging' => 'https://uatpayment.ozopay.com/PaymentEntry/PaymentOption',
            'production' => 'https://checkout.ozopay.com/Paymententry/PaymentOption',
        ],
    ];

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $providers = [],
        $value = [],
        $form = null
    ) {
        $this->form = $form;
        $this->providers = $providers;
        $this->value = $value;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.payment-gateway');
    }
}