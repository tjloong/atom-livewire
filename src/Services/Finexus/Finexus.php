<?php

namespace Jiannius\Atom\Services\Finexus;

use Illuminate\Support\Facades\Storage;

// Finexus integration helper
// ---------------------------
// in AppServiceProvider.php - boot()
//     $this->app->bind('finexus', fn() => new \Jiannius\Atom\Services\Finexus\Finexus);
//     app('finexus')->importPayMaster();

class Finexus
{
    private $country = 'MY';
    private $currency = '458';
    private $configFile = 'finexus.ini';

    // import pay master - to be called in AppServiceProvider
    public function importPayMaster() : void
    {
        $basepath = 'phar://'.__DIR__.'/PayMaster/PayMaster.phar/';
        include_once $basepath.'com/PayMaster/Import/ImportFile.php';

        $import = new \com\PayMaster\Import\ImportFile();
        $import->includeFile($basepath);
    }

    // checkout
    // parameters please refer: https://sandbox.finexusgroup.com/myxaas/docs/PayMaster-MPI/upp/set-parameters/payment-parameters
    public function checkout($parameters = [], $refreshConfigs = false) : mixed
    {
        $this->generateConfigsFile($refreshConfigs);

        // Declare variable for Payment Master Properties - 1st Parameter = File Path , 2nd Parameter = File name
        $propertiesReader = new \com\PayMaster\PropertiesReader\PropertiesReader();

        //Path to the root project folder
        $propertiesReader->PropertiesReader(storage_path('app/'.$this->configFile));

        // Declare variable for Payment Master Entity
        $paymentRequestEntity = new \com\PayMaster\Entities\PayMasterEntity();

        // Get all the parameter values from the user side and set into Payment Master Entity
        foreach ($this->getParameters($parameters) as $key => $val) {
            $paymentRequestEntity->setter($key, $val);
        }

        // Call Payment Master Payment Request Message Builder to generate the message
        $paymentRequestMessageBuilder = new \com\PayMaster\MessageRequestBuilder\PaymentRequestMessageBuilder();
        $paymentMessage = $paymentRequestMessageBuilder->buildPaymentRequestMessage($paymentRequestEntity, $propertiesReader);

        return redirect(settings('finexus_url').'?'.$paymentMessage);
    }

    // get parameters
    // for parameters help please refer: https://sandbox.finexusgroup.com/myxaas/docs/PayMaster-MPI/upp/set-parameters/payment-parameters
    public function getParameters($data) : array
    {
        return [
            'PaymentID' => 'U01', // U01 - UPP transaction, U02 - UPP transaction status query
            'EcommMerchInd' => '1',
            'MerchRefNo' => '', // application's order number / payment number / transaction number
            'CountryCode' => $this->country,
            'CurrCode' => $this->currency,
            'TxnAmt' => '',
            'ExpTxnAmt' => '2',
            'TokenFlag' => 'N',
            'PreAuthFlag' => 'N',
            ...$data,
        ];
    }

    // generate configs file
    public function generateConfigsFile($refresh) : void
    {
        if ($refresh || !Storage::exists($this->configFile)) {
            $configs = [
                'VersionNo' => '06',
                'ServiceID' => 'FNX',
                'MerchantID' => settings('finexus_merchant_id'),
                'TxnChannel' => 'WEB',
                'LangLocale' => 'en',
                'SuccRespURL' => route('__finexus.success'),
                'UnsuccRespURL' => route('__finexus.failed'),
                'CancelRespURL' => route('__finexus.cancel'),
                'QueryRespURL' => route('__finexus.query'),
                'SHAlgorithmType' => 'SH2',
                'SecretKey' => settings('finexus_secret_key'),
                'PaymasterEntryPoint' => __DIR__.'/PayMaster/PaymasterEntryPoint.json',
                'UPPSchema' => __DIR__.'/PayMaster/UPPPaymentSchema.json',
                'query_gateway' => settings('finexus_query_url'),
            ];

            $content = collect($configs)->map(fn($val, $key) => "$key=$val")->join("\n");

            Storage::put($this->configFile, $content);
        }
    }

    // test checkout
    public function test() : mixed
    {
        return $this->checkout([
            'PaymentID' => 'U01',
            'MerchRefNo' => 'TESTING-'.time(),
            'CurrCode' => '458',
            'TxnAmt' => '1.00',
            'ExpTxnAmt' => '2',
            'EcommMerchInd' => '1',
            'CountryCode' => 'MY',
            'TokenFlag' => 'N',
            'PreAuthFlag' => 'N',
        ]);
    }

    // set country
    public function country($name) : mixed
    {
        $this->country = $name;

        return $this;
    }

    // set currency
    public function currency($code) : mixed
    {
        $codes = [
            'AED' => '784',
            'AFN' => '971',
            'ALL' => '008',
            'AMD' => '051',
            'ANG' => '532',
            'AOA' => '973',
            'ARS' => '032',
            'AUD' => '036',
            'AWG' => '533',
            'AZN' => '944',
            'BAM' => '977',
            'BBD' => '052',
            'BDT' => '050',
            'BGN' => '975',
            'BHD' => '048',
            'BIF' => '108',
            'BMD' => '060',
            'BND' => '096',
            'BOB' => '068',
            'BOV' => '984',
            'BRL' => '986',
            'BSD' => '044',
            'BTN' => '064',
            'BWP' => '072',
            'BYN' => '933',
            'BZD' => '084',
            'CAD' => '124',
            'CDF' => '976',
            'CHE' => '947',
            'CHF' => '756',
            'CHW' => '948',
            'CLF' => '990',
            'CLP' => '152',
            'CNY' => '156',
            'COP' => '170',
            'COU' => '970',
            'CRC' => '188',
            'CUP' => '192',
            'CVE' => '132',
            'CZK' => '203',
            'DJF' => '262',
            'DKK' => '208',
            'DOP' => '214',
            'DZD' => '012',
            'EGP' => '818',
            'ERN' => '232',
            'ETB' => '230',
            'EUR' => '978',
            'FJD' => '242',
            'FKP' => '238',
            'GBP' => '826',
            'GEL' => '981',
            'GHS' => '936',
            'GIP' => '292',
            'GMD' => '270',
            'GNF' => '324',
            'GTQ' => '320',
            'GYD' => '328',
            'HKD' => '344',
            'HNL' => '340',
            'HTG' => '332',
            'HUF' => '348',
            'IDR' => '360',
            'ILS' => '376',
            'INR' => '356',
            'IQD' => '368',
            'IRR' => '364',
            'ISK' => '352',
            'JMD' => '388',
            'JOD' => '400',
            'JPY' => '392',
            'KES' => '404',
            'KGS' => '417',
            'KHR' => '116',
            'KMF' => '174',
            'KPW' => '408',
            'KRW' => '410',
            'KWD' => '414',
            'KYD' => '136',
            'KZT' => '398',
            'LAK' => '418',
            'LBP' => '422',
            'LKR' => '144',
            'LRD' => '430',
            'LSL' => '426',
            'LYD' => '434',
            'MAD' => '504',
            'MDL' => '498',
            'MGA' => '969',
            'MKD' => '807',
            'MMK' => '104',
            'MNT' => '496',
            'MOP' => '446',
            'MRU' => '929',
            'MUR' => '480',
            'MVR' => '462',
            'MWK' => '454',
            'MXN' => '484',
            'MXV' => '979',
            'MYR' => '458',
            'MZN' => '943',
            'NAD' => '516',
            'NGN' => '566',
            'NIO' => '558',
            'NOK' => '578',
            'NPR' => '524',
            'NZD' => '554',
            'OMR' => '512',
            'PAB' => '590',
            'PEN' => '604',
            'PGK' => '598',
            'PHP' => '608',
            'PKR' => '586',
            'PLN' => '985',
            'PYG' => '600',
            'QAR' => '634',
            'RON' => '946',
            'RSD' => '941',
            'RUB' => '643',
            'RWF' => '646',
            'SAR' => '682',
            'SBD' => '090',
            'SCR' => '690',
            'SDG' => '938',
            'SEK' => '752',
            'SGD' => '702',
            'SHP' => '654',
            'SLE' => '925',
            'SLL' => '694',
            'SOS' => '706',
            'SRD' => '968',
            'SSP' => '728',
            'STN' => '930',
            'SVC' => '222',
            'SYP' => '760',
            'SZL' => '748',
            'THB' => '764',
            'TJS' => '972',
            'TMT' => '934',
            'TND' => '788',
            'TOP' => '776',
            'TRY' => '949',
            'TTD' => '780',
            'TWD' => '901',
            'TZS' => '834',
            'UAH' => '980',
            'UGX' => '800',
            'USD' => '840',
            'USN' => '997',
            'UYI' => '940',
            'UYU' => '858',
            'UYW' => '927',
            'UZS' => '860',
            'VED' => '926',
            'VES' => '928',
            'VND' => '704',
            'VUV' => '548',
            'WST' => '882',
            'XAF' => '950',
            'XAG' => '961',
            'XAU' => '959',
            'XBA' => '955',
            'XBB' => '956',
            'XBC' => '957',
            'XBD' => '958',
            'XCD' => '951',
            'XDR' => '960',
            'XOF' => '952',
            'XPD' => '964',
            'XPF' => '953',
            'XPT' => '962',
            'XSU' => '994',
            'XTS' => '963',
            'XUA' => '965',
            'XXX' => '999',
            'YER' => '886',
            'ZAR' => '710',
            'ZMW' => '967',
            'ZWL' => '932',
        ];

        $this->currency = get($codes, $code);

        return $this;
    }
}