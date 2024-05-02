<?php

namespace Jiannius\Atom\Services\Finexus;

use com\PayMaster\Entities\PayMasterEntity;
use com\PayMaster\Import\ImportFile;
use com\PayMaster\MessageRequestBuilder\PaymentRequestMessageBuilder;
use com\PayMaster\MessageResponseBuilder\PaymentResponseMessageBuilder;
use com\PayMaster\MessageRequestBuilder\WebServicePaymentRequestMessageBuilder;
use com\PayMaster\PropertiesReader\PropertiesReader;
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
    private $configFilePath = 'finexus.ini';

    private $currencyCodes = [
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

    private $statusCodes = [
        '00' => 'Approved',
        '01' => 'Refer To Issuer',
        '02' => 'Refer To Issuer; Special Condition',
        '03' => 'Invalid Merchant ID',
        '04' => 'Pick Up Card',
        '05' => 'Do not Honour',
        '06' => 'Error',
        '07' => 'Pick Up Card',
        '11' => 'Transaction Voided',
        '12' => 'Invalid Transaction',
        '13' => 'Invalid Amount',
        '14' => 'Invalid Card Number (PAN)',
        '19' => 'Re-enter Transaction',
        '21' => 'No Action Taken (Unable to cack out prior transaction)',
        '22' => 'Transaction in pending stage (in progress)',
        '23' => 'Invalid/Unaccaptable Transaction Fee',
        '25' => 'Terminated/ Inactive card',
        '30' => 'Message Format Error',
        '31' => 'Bank ID Not Found',
        '41' => 'Card Reported Lost',
        '43' => 'Stolen Card',
        '44' => 'PIN Change Require',
        '45' => 'Card Not Activated For Use',
        '51' => 'Insufficient Fund',
        '52' => 'No Checking Count',
        '53' => 'No Savings Account',
        '54' => 'Expired Card',
        '55' => 'Invalid PIN',
        '56' => 'Invalid Card',
        '57' => 'Transaction not permitted to cardholder',
        '58' => 'Invalid Transaction',
        '59' => 'Suspected Fraud',
        '61' => 'Over Limit',
        '62' => 'Restricted Card',
        '63' => 'Security Violation',
        '68' => 'Transaction timeout (No reply from acquirer)',
        '75' => 'PIN Tries Exceeded',
        '76' => 'Unable to locate previous message',
        '77' => 'Repeat or Reversal Data are inconsistent',
        '78' => 'Card is not activated yet',
        '80' => 'Credit issuer unavaliable',
        '83' => 'Unable to verify PIN',
        '88' => 'Call Issuer',
        '91' => 'Issuer/ Switch Error',
        '92' => 'Destination cannot be found for routing',
        '93' => 'Transaction cannot be completed; violation of law',
        '94' => 'Duplicate Transaction',
        '95' => 'Total Mismatch (reconcillation problem)',
        '96' => 'System Malfunction/Error',
        '99' => 'Unknown/unmappable response code',
        'P1' => 'Failed 3D Authentication',
        'N7' => 'Decline for CVV2 failure',
    ];

    // import pay master - to be called in AppServiceProvider
    public function importPayMaster() : void
    {
        $basepath = 'phar://'.__DIR__.'/PayMaster/PayMaster.phar/';
        include_once $basepath.'com/PayMaster/Import/ImportFile.php';

        $import = new ImportFile();
        $import->includeFile($basepath);
    }

    // checkout
    // parameters please refer: https://sandbox.finexusgroup.com/myxaas/docs/PayMaster-MPI/upp/set-parameters/payment-parameters
    public function checkout($parameters = []) : mixed
    {
        $parameters = [
            'PaymentID' => 'U01', // U01 - UPP transaction, U02 - UPP transaction status query
            'EcommMerchInd' => '1',
            'MerchRefNo' => '', // application's order number / payment number / transaction number
            'CountryCode' => $this->country,
            'CurrCode' => $this->currency,
            'TxnAmt' => '',
            'ExpTxnAmt' => '2',
            'TokenFlag' => 'N',
            'PreAuthFlag' => 'N',
            ...$parameters,
        ];

        $propertiesReader = $this->getPropertiesReader();
        $paymentRequestEntity = new PayMasterEntity();

        foreach ($parameters as $key => $val) {
            if ($key === 'UserContact') $val = preg_replace("/[^0-9]/", "", $val);
            $paymentRequestEntity->setter($key, $val);
        }

        $paymentRequestMessageBuilder = new PaymentRequestMessageBuilder();
        $paymentMessage = $paymentRequestMessageBuilder->buildPaymentRequestMessage($paymentRequestEntity, $propertiesReader);

        $this->deleteConfigFile();

        return redirect(settings('finexus_url').'?'.$paymentMessage);
    }

    // parse response
    public function parseResponse($response) : mixed
    {
        $propertiesReader = $this->getPropertiesReader();

        $paymentResponseEntity = new PayMasterEntity();
        $paymentResponseEntity->setter("ResponseMessage", $response);

        $paymentResponseMessageBuilder = new PaymentResponseMessageBuilder();
        $paymentResponseMessageBuilder->buildUPPPaymentResponseMessage($paymentResponseEntity, $propertiesReader);

        $code = $paymentResponseEntity->getter('TxnStatDetCde');

        $this->deleteConfigFile();

        // invalid hash value
        if ($code === '5015') abort(500, 'Invalide secure hash value.');

        return $paymentResponseEntity->getPayMasterEntity();
    }

    // query
    public function query($data) : mixed
    {
        $propertiesReader = $this->getPropertiesReader();

        $paymentRequestEntity = new PayMasterEntity();
        $paymentRequestEntity->setter('PaymentID', 'U02'); 
        $paymentRequestEntity->setter('MerchantID', get($data, 'MerchantID'));
        $paymentRequestEntity->setter('ServiceID', get($data, 'ServiceID'));
        $paymentRequestEntity->setter('MerchRefNo', get($data, 'MerchRefNo'));

        $WebServicePaymentRequestMessageBuilder = new WebServicePaymentRequestMessageBuilder();
        $WebServicePaymentRequestMessageBuilder->buildUPPQueryRequestMessage($paymentRequestEntity, $propertiesReader);

        $this->deleteConfigFile();

        return $paymentRequestEntity->getPayMasterEntity();
    }

    // get properties reader
    public function getPropertiesReader() : mixed
    {
        $propertiesReader = new PropertiesReader();
        $propertiesReader->PropertiesReader($this->getConfigFile());

        return $propertiesReader;
    }

    // get config file
    public function getConfigFile() : string
    {
        if (!Storage::exists($this->configFilePath)) {
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

            Storage::put($this->configFilePath, $content);
        }

        return storage_path('app/'.$this->configFilePath);
    }

    // delete config file
    public function deleteConfigFile() : void
    {
        if (Storage::exists($this->configFilePath)) Storage::delete($this->configFilePath);
    }

    // test checkout
    public function test() : bool
    {
        $query = $this->query([
            'MerchRefNo' => 'TESTING-'.time(),
        ]);

        return get($query, 'QueryStatus') === '12'; // status 12 = transaction not found
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
        $this->currency = get($this->currencyCodes, $code);

        return $this;
    }

    // get status
    public function status($response) : string
    {
        $code = get($response, 'TxnStatus');

        return pick([
            'pending' => $code === '22',
            'success' => $code === '00',
            'failed' => true,
        ]);
    }
}