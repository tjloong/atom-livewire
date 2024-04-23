<?php

namespace Jiannius\Atom\Services\Finexus;

// Finexus integration helper
// ---------------------------
// in AppServiceProvider.php - boot()
//     $this->app->bind('finexus', fn() => new \Jiannius\Atom\Services\Finexus\Finexus);
//     app('finexus')->importPayMaster();

class Finexus
{
    // import pay master - to be called in AppServiceProvider
    public function importPayMaster() : void
    {
        $basepath = 'phar://'.__DIR__.'/PayMaster/PayMaster.phar/';
        include_once $basepath.'com/PayMaster/Import/ImportFile.php';

        $import = new \com\PayMaster\Import\ImportFile();
        $import->includeFile($basepath);
    }

    // checkout
    public function checkout($payload = [], $type = 'upp') : mixed
    {
        $ini = [
            'upp' => 'UPPPayment.ini',
            'qr' => 'QRPayment.ini',
            'obw' => 'DDPayment.ini',
        ][$type];

        // Declare variable for Payment Master Properties - 1st Parameter = File Path , 2nd Parameter = File name
        $propertiesReader = new \com\PayMaster\PropertiesReader\PropertiesReader();

        //Path to the root project folder
        $propertiesReader->PropertiesReader(__DIR__."/PayMaster/$ini");

        // Declare variable for Payment Master Entity
        $paymentRequestEntity = new \com\PayMaster\Entities\PayMasterEntity();

        // Get all the parameter values from the user side and set into Payment Master Entity
        foreach ($payload as $key => $val) {
            $paymentRequestEntity->setter($key, $val);
        }

        // Call Payment Master Payment Request Message Builder to generate the message
        $paymentRequestMessageBuilder = new \com\PayMaster\MessageRequestBuilder\PaymentRequestMessageBuilder();
        $paymentMessage = $paymentRequestMessageBuilder->buildPaymentRequestMessage($paymentRequestEntity, $propertiesReader);

        dd($paymentMessage);

        $url = app()->environment('production')
            ? 'https://sandbox.finexusgroup.com/upp/faces/upp/payment.xhtml'
            : 'https://sandbox.finexusgroup.com/upp/faces/upp/payment.xhtml';

        return redirect($url.'?'.$paymentMessage);
    }
}