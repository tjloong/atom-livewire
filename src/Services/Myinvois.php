<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Facades\Http;
use Jiannius\Atom\Atom;

class Myinvois
{
    public $settings;
    public $failedCallback;

    public function __construct($settings = [])
    {
        $this->settings = collect([
            'client_id' => '337518d1-3ea9-42d4-8a97-b6e2a87d5766', // settings('myinvois_client_id') ?? env('MYINVOIS_CLIENT_ID'),
            'client_secret' => 'ddc2d4ed-074f-4509-abdb-0b60512381fa', // settings('myinvois_client_secret') ?? env('MYINVOIS_CLIENT_SECRET'),
            'grant_type' => 'client_credentials',
            'scope' => 'InvoicingAPI',
            'on_behalf_of' => null,
            'ca_path' => null,
            ...$settings,
        ]);
    }

    public function getToken()
    {
        $cachekey = collect([
            'myinvois',
            $this->settings->get('client_id'),
            $this->settings->get('on_behalf_of'), // for intermediary login
        ])->filter()->join('_');

        $cache = cache($cachekey);
        $token = get($cache, 'access_token');
        $expiry = get($cache, 'expired_at');

        if ($token && $expiry?->isFuture()) return $token;

        cache()->forget($cachekey);

        $response = Http::withHeaders([
            'onbehalfof' => $this->settings->get('on_behalf_of'),
        ])->asForm()->post(
            url: $this->getEndpoint('/connect/token'),
            data: $this->settings->only(['client_id', 'client_secret', 'grant_type', 'scope'])->toArray(),
        );

        $response->throw();

        cache()->put($cachekey, [
            ...$response->json(),
            'expired_at' => now()->addMinutes(50),
        ]);

        return $this->getToken();

    }

    public function getEndpoint($uri)
    {
        $base = settings('myinvois_base_url') ?? env('MYINVOIS_BASE_URL') ?? (
            app()->environment('production')
                ? 'https://myinvois.hasil.gov.my'
                : 'https://preprod-api.myinvois.hasil.gov.my'
        );

        $prefix = str($uri)->startsWith('/') ? '' : '/api/v1.0/';

        return $base.$prefix.$uri;
    }

    public function whenFailed($callback)
    {
        $this->failedCallback = $callback;

        return $this;
    }

    public function callApi($uri, $method = 'GET', $data = []) : mixed
    {
        $method = strtolower($method);
        $token = $this->getToken();

        if (!$token) abort(500, 'Missing MyInvois API access token');

        $res = Http::withToken($token)->$method($this->getEndpoint($uri), $data);

        if ($res->failed() && ($callback = $this->failedCallback)) {
            return $callback($res);
        }

        return $res;
    }

    public function validateTaxpayerTIN($data = [])
    {
        $api = $this->callApi(
            uri: 'taxpayer/validate/'.get($data, 'tin'),
            data: [
                'idType' => get($data, 'idType'),
                'idValue' => get($data, 'idValue'),
            ],
        );

        return $api->ok();
    }

    public function getRecentDocuments($data = [])
    {
        $api = $this->callApi(
            uri: 'documents/recent',
            data: $data,
        );

        return $api->json();
    }

    public function getSubmission($data = [])
    {
        $api = $this->callApi(
            uri: 'documentsubmissions/'.get($data, 'uid'),
            data: [
                'pageNo' => get($data, 'pageNo'),
                'pageSize' => get($data, 'pageSize'),
            ],
        );

        return $api->json();
    }

    public function getDocument($data = [])
    {
        $api = $this->callApi(
            uri: 'documents/'.get($data, 'uid').'/raw',
        );

        return $api->json();
    }

    public function getDocumentDetails($data = [])
    {
        $api = $this->callApi(
            uri: 'documents/'.get($data, 'uid').'/details',
        );

        return $api->json();
    }

    public function searchDocuments($data = [])
    {
        $api = $this->callApi(
            uri: 'documents/search',
            data: $data,
        );

        return $api->json();
    }

    public function submitDocuments($data = [])
    {
        $api = $this->callApi(
            uri: 'documentsubmissions',
            method: 'POST',
            data: [
                'documents' => collect($data)->map(function ($item) {
                    $document = $this->getDocumentUBLSchema($item);
                    $base64 = base64_encode($document->toJson());

                    return [
                        'format' => 'JSON',
                        'document' => $base64,
                        'documentHash' => hash('sha256', $base64),
                        'codeNumber' => get($item, 'number'),
                    ];
                })->toArray(),
            ],
        );

        return $api->json();
    }

    public function cancelDocument($data = [])
    {
        $api = $this->callApi(
            uri: 'documents/state/'.get($data, 'uid').'/state',
            data: [
                'status' => 'cancelled',
                'reason' => get($data, 'reason'),
            ],
        );

        return $api->json();
    }

    public function rejectDocument($data = [])
    {
        $api = $this->callApi(
            uri: 'documents/state/'.get($data, 'uid').'/state',
            data: [
                'status' => 'rejected',
                'reason' => get($data, 'reason'),
            ],
        );

        return $api->json();
    }

    public function getCode($name, $key)
    {
        $codes = Atom::options('myinvois.'.$name);

        $code = match ($name) {
            'document_types', 'product_categories', 'msic_codes', 'tax_types' => get(collect($codes)->firstWhere('Description', $key), 'Code'),
            'document_versions' => get(collect($codes)->firstWhere('Description', $key), 'Version'),
            'state_codes' => get(collect($codes)->firstWhere('State', $key), 'Code'),
            'country_codes' => get(collect($codes)->firstWhere('Country', strtoupper($key)), 'Code'),
            'currency_codes' => get(collect($codes)->firstWhere('Currency', $key), 'Code'),
            'payment_modes' => get(collect($codes)->firstWhere('Payment Method', $key), 'Code'),
            'unit_of_measurements' => get(collect($codes)->firstWhere('Name', $key), 'Code'),
        };

        return $code ?? $key;
    }

    public function getDocumentUBLSchema($data)
    {
        $schema = collect();

        $schema->put('_D', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
        $schema->put('_A', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $schema->put('_B', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $schema->put('Invoice', [
            [
                ...$this->getDocumentEssentialSchema($data),
                ...$this->getDocumentCurrencySchema($data),
                ...$this->getDocumentSupplierSchema($data),
                ...$this->getDocumentBuyerSchema($data),
                ...$this->getDocumentBillingPeriodSchema($data),
                ...$this->getDocumentReferencesSchema($data),
                ...$this->getDocumentShippingSchema($data),
                ...$this->getDocumentPrepaidSchema($data),
                ...$this->getDocumentPaymentModeSchema($data),
                ...$this->getDocumentChargesAndDiscountsSchema($data),
                ...$this->getDocumentTotalsSchema($data),    
            ],
        ]);

        dd($schema->toArray());

        return $schema;
    }

    public function getDocumentEssentialSchema($data)
    {
        $schema = [];

        data_set($schema, 'ID.0._', get($data, 'number'));
        data_set($schema, 'IssueDate.0._', get($data, 'issued_at')->toDateString());
        data_set($schema, 'IssueTime.0._', get($data, 'issued_at')->format('H:i:sp'));
        data_set($schema, 'InvoiceTypeCode.0._', $this->getCode('document_types', get($data, 'document_type')));
        data_set($schema, 'InvoiceTypeCode.0.listVersionID', $this->getCode('document_versions', get($data, 'document_type')));

        return $schema;
    }

    public function getDocumentCurrencySchema($data)
    {
        $schema = [];
        $currency = get($data, 'currency');
        $rate = get($data, 'currency_rate');

        data_set($schema, 'DocumentCurrencyCode.0._', $currency);
        data_set($schema, 'TaxCurrencyCode.0._', $currency);

        if ($rate) {
            data_set($schema, 'TaxExchangeRate.0.CalculationRate.0._', $rate);
            data_set($schema, 'TaxExchangeRate.0.SourceCurrencyCode.0._', 'DocumentCurrencyCode');
            data_set($schema, 'TaxExchangeRate.0.TargetCurrencyCode.0._', 'MYR');
        }

        return $schema;
    }

    public function getDocumentSupplierSchema($data)
    {
        $schema = [];
        $supplier = get($data, 'supplier');

        data_set($schema, 'AccountingSupplierParty.0.Party.0.PartyLegalEntity.0.RegistrationName.0._', get($supplier, 'name'));

        foreach ($this->getDocumentTINSchema($supplier) as $key => $val) {
            data_set($schema, 'AccountingSupplierParty.0.Party.0.'.$key, $val);
        }

        foreach ($this->getDocumentAddressSchema($supplier) as $key => $val) {
            data_set($schema, 'AccountingSupplierParty.0.Party.0.'.$key, $val);
        }

        foreach ($this->getDocumentContactSchema($supplier) as $key => $val) {
            data_set($schema, 'AccountingSupplierParty.0.Party.0.'.$key, $val);
        }

        if ($acc = get($supplier, 'bank_account_number')) {
            data_set($schema, 'PaymentMeans.0.PayeeFinancialAccount.0.ID.0._', $acc);
        }    

        if ($certex = get($supplier, 'certex')) { // authorized certified exporter
            data_set($schema, 'AccountingSupplierParty.0.AdditionalAccountID.0._', $certex);
            data_set($schema, 'AccountingSupplierParty.0.AdditionalAccountID.0.schemeAgencyName', 'CertEX');
        }

        // Malaysia Standard Industrial Classification
        if ($msicCode = get($supplier, 'msic_code')) {
            data_set($schema, 'AccountingSupplierParty.0.Party.0.IndustryClassificationCode.0._', $msicCode);

            if ($msicDescription = get($supplier, 'msic_description')) {
                data_set($schema, 'AccountingSupplierParty.0.Party.0.IndustryClassificationCode.0.name', $msicDescription);
            }
        }

        return $schema;
    }

    public function getDocumentBuyerSchema($data)
    {
        $schema = [];
        $buyer = get($data, 'buyer');

        data_set($document, 'AccountingCustomerParty.0.Party.0.PartyLegalEntity.0.RegistrationName.0._', get($buyer, 'name'));

        foreach ($this->getDocumentTINSchema($buyer) as $key => $val) {
            data_set($schema, 'AccountingCustomerParty.0.Party.0.'.$key, $val);
        }

        foreach ($this->getDocumentAddressSchema($buyer) as $key => $val) {
            data_set($schema, 'AccountingCustomerParty.0.Party.0.'.$key, $val);
        }

        foreach ($this->getDocumentContactSchema($buyer) as $key => $val) {
            data_set($schema, 'AccountingCustomerParty.0.Party.0.'.$key, $val);
        }

        return $schema;
    }

    public function getDocumentBillingPeriodSchema($data)
    {
        $schema = [];
        $billing = get($data, 'billing');

        foreach(collect([
            'InvoicePeriod.0.StartDate.0._' => optional(get($billing, 'start_at'))->toDateString(),
            'InvoicePeriod.0.EndDate.0._' => optional(get($billing, 'end_at'))->toDateString(),
            'InvoicePeriod.0.Description.0._' => get($billing, 'frequency'), // Daily, Weekly, Biweekly, Monthly, Bimonthly, Quarterly, Half-yearly, Yearly, Others / Not Applicable
            'BillingReference.0.AdditionalDocumentReference.0.ID.0._' => get($billing, 'reference'),
        ])->filter() as $key => $val) {
            data_set($schema, $key, $val);
        }

        return $schema;
    }

    public function getDocumentReferencesSchema($data)
    {
        $schema = [];
        $refs = get($data, 'references');

        foreach ($refs as $i => $ref) {
            if ($num = get($ref, 'reference')) {
                data_set($schema, 'AdditionalDocumentReference.'.$i.'.ID.0._', $num);
            }
            if ($type = get($ref, 'type')) {
                data_set($schema, 'AdditionalDocumentReference.'.$i.'.DocumentType.0._', match ($type) {
                    'CUSTOMS' => 'CustomsImportForm',
                    'FTA' => 'FreeTradeAgreement',
                    default => $type,
                });
            }
            if ($desc = get($ref, 'description')) {
                data_set($schema, 'AdditionalDocumentReference.'.$i.'.DocumentDescription.0._', $desc);
            }
        }

        return $schema;
    }

    public function getDocumentShippingSchema($data)
    {
        $schema = [];
        $shipping = get($data, 'shipping');

        if ($name = get($shipping, 'name')) {
            data_set($schema, 'Delivery.0.DeliveryParty.0.PartyLegalEntity.0.RegistrationName.0._', $name);
        }

        foreach ($this->getDocumentTINSchema($shipping) as $key => $val) {
            data_set($schema, 'Delivery.0.DeliveryParty.0.'.$key, $val);
        }

        foreach ($this->getDocumentAddressSchema($shipping) as $key => $val) {
            data_set($schema, 'Delivery.0.DeliveryParty.0.'.$key, $val);
        }

        if ($ref = get($shipping, 'reference')) {
            data_set($schema, 'Delivery.0.Shipment.0.ID.0._', $ref);
        }

        if ($amount = get($shipping, 'amount')) {
            data_set($schema, 'Delivery.0.Shipment.0.FreightAllowanceCharge.0.ChargeIndicator.0._', true);
            data_set($schema, 'Delivery.0.Shipment.0.FreightAllowanceCharge.0.Amount.0._', $amount);
        }

        if ($currency = get($shipping, 'currency')) {
            data_set($schema, 'Delivery.0.Shipment.0.FreightAllowanceCharge.0.Amount.0.currencyID', $this->getCode('currency_codes', $currency));
        }

        if ($desc = get($shipping, 'description')) {
            data_set($schema, 'Delivery.0.Shipment.0.FreightAllowanceCharge.0.AllowanceChargeReason.0._', $desc);
        }

        return $schema;
    }

    public function getDocumentPrepaidSchema($data)
    {
        $schema = [];
        $prepaid = get($data, 'prepaid');

        if ($ref = get($prepaid, 'reference')) {
            data_set($schema, 'PrepaidPayment.0.ID.0._', $ref);
        }

        if ($amount = get($prepaid, 'amount')) {
            data_set($schema, 'PrepaidPayment.0.PaidAmount.0._', $amount);
        }

        if ($currency = get($prepaid, 'currency')) {
            data_set($schema, 'PrepaidPayment.0.PaidAmount.0.currencyID', $this->getCode('currency_codes', $currency));
        }

        if ($dt = get($prepaid, 'paid_at')) {
            data_set($schema, 'PrepaidPayment.0.PaidDate.0._', $dt->toDateString());
            data_set($schema, 'PrepaidPayment.0.PaidTime.0._', $dt->format('H:i:sp'));
        }

        return $schema;
    }

    public function getDocumentPaymentModeSchema($data)
    {
        $schema = [];

        if ($paymode = get($data, 'payment_mode')) {
            data_set($schema, 'PaymentMeans.0.PaymentMeansCode.0._', $this->getCode('payment_modes', $paymode));
        }

        if ($payterm = get($data, 'payment_term')) {
            data_set($schema, 'PaymentTerms.0.Note.0._', $payterm);
        }

        return $schema;
    }

    public function getDocumentChargesAndDiscountsSchema($data)
    {
        $schema = [];
        $charges = get($data, 'charges', []);
        $discounts = get($data, 'discounts', []);
        $currency = get($data, 'currency');
        $items = collect($charges)->concat(
            collect($discounts)->map(fn ($discount) => [...$discount, 'is_discount' => true])
        );

        foreach ($items as $i => $item) {
            data_set($schema, 'AllowanceCharge.'.$i.'.ChargeIndicator.0._', get($item, 'is_discount') ? false : true);

            if ($amount = get($item, 'amount')) {
                data_set($schema, 'AllowanceCharge.'.$i.'.Amount.0._', $amount);
                data_set($schema, 'AllowanceCharge.'.$i.'.Amount.0.currencyID', $this->getCode('currency_codes', $currency));
            }

            if ($desc = get($item, 'description')) {
                data_set($schema, 'AllowanceCharge.'.$i.'.AllowanceChargeReason.0._', $desc);
            }
        }

        return $schema;
    }

    public function getDocumentTotalsSchema($data)
    {
        $schema = [];

        $currency = get($data, 'currency');
        $subtotal = get($data, 'subtotal');
        $grandTotal = get($data, 'grand_total');
        $payableTotal = get($data, 'payable_total') ?: $grandTotal;

        data_set($schema, 'LegalMonetaryTotal.0.TaxExclusiveAmount.0._', $subtotal);
        data_set($schema, 'LegalMonetaryTotal.0.TaxExclusiveAmount.0.currencyID', $currency);

        data_set($schema, 'LegalMonetaryTotal.0.TaxInclusiveAmount.0._', $grandTotal);
        data_set($schema, 'LegalMonetaryTotal.0.TaxInclusiveAmount.0.currencyID', $currency);

        data_set($schema, 'LegalMonetaryTotal.0.PayableAmount.0._', $payableTotal);
        data_set($schema, 'LegalMonetaryTotal.0.PayableAmount.0.currencyID', $currency);

        foreach ($this->getDocumentTaxesSchema(get($data, 'taxes', []), $currency) as $key => $val) {
            data_set($schema, $key, $val);
        }

        return $schema;
    }

    public function getDocumentLineItemsSchema($data)
    {
        $schema = [];
        $currency = get($data, 'currency');

        foreach (get($data, 'line_items', []) as $i => $item) {
            data_set($schema, 'InvoiceLine.'.$i.'.ID.0._', (string) str($i + 1)->padLeft(3, '0'));
            data_set($schema, 'InvoiceLine.'.$i.'.InvoicedQuantity.0._', number_format(get($item, 'qty'), 3));
            data_set($schema, 'InvoiceLine.'.$i.'.InvoicedQuantity.0.unitCode', get($item, 'uom'));
            data_set($schema, 'InvoiceLine.'.$i.'.Item.0.Description.0._', get($item, 'description'));
            data_set($schema, 'InvoiceLine.'.$i.'.Price.0.PriceAmount.0._', get($item, 'unit_price'));
            data_set($schema, 'InvoiceLine.'.$i.'.Price.0.PriceAmount.0.currencyID', $currency);
            
            if ($country = get($item, 'country')) {
                data_set($schema, 'InvoiceLine.'.$i.'.Item.0.OriginCountry.0.IdentificationCode.0._', $country);
            }

            foreach (collect(get($item, 'classifications'))->concat(
                collect(get($item, 'tariffs'))->map(fn ($tariff) => [...$tariff, 'is_tariff' => true])
            ) as $j => $classification) {
                data_set($schema, 'InvoiceLine.'.$i.'.Item.0.CommodityClassification.'.$j.'.ItemClassificationCode.0._', get($classification, 'code'));
                data_set($schema, 'InvoiceLine.'.$i.'.Item.0.CommodityClassification.'.$j.'.ItemClassificationCode.0.listID', get($classification, 'is_tariff') ? 'PTC' : 'CLASS');
            }

            foreach ($this->getDocumentTaxesSchema(get($item, 'taxes', []), $currency) as $key => $val) {
                data_set($schema, 'InvoiceLine.'.$i.'.'.$key, $val);
            }

            // subtotal - qty * unit price
            data_set($schema, 'InvoiceLine.'.$i.'.ItemPriceExtension.0.Amount.0._', get($item, 'subtotal'));
            data_set($schema, 'InvoiceLine.'.$i.'.ItemPriceExtension.0.Amount.0.currencyID', $currency);
            
            // total excluding tax - subtotal + discount
            data_set($schema, 'InvoiceLine.'.$i.'.LineExtensionAmount.0._', get($item, 'subtotal') + get($item, 'discount'));
            data_set($schema, 'InvoiceLine.'.$i.'.LineExtensionAmount.0.currencyID', $currency);

            if (get($item, 'discount')) {
                data_set($schema, 'InvoiceLine.'.$i.'.AllowanceCharge.0.ChargeIndicator.0._', false);
                data_set($schema, 'InvoiceLine.'.$i.'.AllowanceCharge.0.Amount.0._', get($item, 'discount.amount'));
                data_set($schema, 'InvoiceLine.'.$i.'.AllowanceCharge.0.Amount.0.currencyID', $currency);
                
                if (get($item, 'discount.description')) {
                    data_set($schema, 'InvoiceLine.'.$i.'.AllowanceCharge.0.AllowanceChargeReason.0._', get($item, 'discount.description'));
                }

                if (get($item, 'discount.rate')) {
                    data_set($schema, 'InvoiceLine.'.$i.'.AllowanceCharge.0.MultiplierFactorNumeric.0._', get($item, 'discount.rate'));
                }
            }
        }

        return $schema;
    }

    public function getDocumentTINSchema($data)
    {
        return collect([
            ['TIN', get($data, 'tin')],
            ['BRN', get($data, 'brn')],
            ['SST', get($data, 'sst')],
            ['TTX', get($data, 'ttx')],
        ])->filter(fn ($val) => !empty($val[1]))->values()->mapWithKeys(fn ($val, $i) => [
            'PartyIdentification.'.$i.'.ID.0._' => $val[0],
            'PartyIdentification.'.$i.'.ID.0.schemaID' => $val[1],
        ])->toArray();
    }

    public function getDocumentContactSchema($data)
    {
        return collect([
            'Contact.0.Telephone.0._' => get($data, 'phone'),
            'Contact.0.ElectronicMail.0._' => get($data, 'email'),
        ])->filter()->toArray();
    }

    public function getDocumentAddressSchema($data)
    {
        $schema = collect([
            'PostalAddress.0.AddressLine.0.Line.0._' => get($data, 'address_line_1'),
            'PostalAddress.0.AddressLine.1.Line.0._' => get($data, 'address_line_2'),
            'PostalAddress.0.AddressLine.2.Line.0._' => get($data, 'address_line_3'),
            'PostalAddress.0.CityName.0._' => get($data, 'city'),
            'PostalAddress.0.PostalZone.0._' => get($data, 'postcode'),
            'PostalAddress.0.CountrySubentityCode.0._' => $this->getCode('state_codes', get($data, 'state')),
        ])->filter();

        if ($country = get($data, 'country')) {
            $schema->put('PostalAddress.0.Country.0.IdentificationCode.0._', $this->getCode('country_codes', $country));
            $schema->put('PostalAddress.0.Country.0.IdentificationCode.0.listID', 'ISO3166-1');
            $schema->put('PostalAddress.0.Country.0.IdentificationCode.0.listAgencyID', '6');
        }

        return $schema->toArray();
    }

    public function getDocumentTaxesSchema($taxes, $currency)
    {
        if (!$taxes) return [];

        $schema = collect();
        $total = collect($taxes)->sum('amount');

        $schema->put('TaxTotal.0.TaxAmount.0._', $total);
        $schema->put('TaxTotal.0.TaxAmount.0.currencyID', $currency);

        foreach ($taxes as $i => $tax) {
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxCategory.0.ID.0._', get($tax, 'code'));
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxCategory.0.TaxScheme.0.ID.0._', 'OTH');
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxCategory.0.TaxScheme.0.ID.0.schemeID', 'UN/ECE 5153');
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxCategory.0.TaxScheme.0.ID.0.schemeAgencyID', '6');
    
            if ($reason = get($tax, 'exemption_reason')) {
                $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxCategory.0.TaxExemptionReason.0._', $reason);
            }
    
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxableAmount.0._', get($tax, 'taxable_amount'));
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxableAmount.0.currencyID', $currency);
    
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxAmount.0._', get($tax, 'amount'));
            $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.TaxAmount.0.currencyID', $currency);
    
            if ($rate = get($tax, 'rate')) {
                $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.Percent.0._', $rate);
            }
            else if (get($tax, 'fixed_rate_base_unit_measure')) {
                $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.BaseUnitMeasure.0._', get($tax, 'fixed_rate_base_unit_measure'));
                $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.BaseUnitMeasure.0.unitCode', get($tax, 'fixed_rate_base_unit_measure_code'));
                $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.PerUnitAmount.0._', get($tax, 'fixed_rate_per_unit_amount'));
                $schema->put('TaxTotal.0.TaxSubtotal.'.$i.'.PerUnitAmount.0.currencyID', $currency);
            }
        }

        return $schema;
    }

    public function getSampleDocument()
    {
        return [
            [
                'number' => 'INV9999900',
                'issued_at' => now(),
                'document_type' => $this->getCode('document_types', 'Invoice'),
                'currency' => $this->getCode('currency_codes', 'MYR'),
                'payment_mode' => $this->getCode('payment_modes', 'Bank Transfer'),
                'payment_term' => '30 days',
                'billing' => [
                    'start_at' => now(),
                    'end_at' => now()->addDays(30),
                    'frequency' => 'Monthly',
                    'reference' => 'BILREF28347923',
                ],
                'references' => [
                    [
                        'reference' => 'E32342348972394',
                        'type' => 'CUSTOMS',
                    ],
                    [
                        'reference' => 'F23489723894',
                        'type' => 'FTA',
                        'description' => 'ASEAN-Australia-New Zealand FTA (AANZFTA)',
                    ],
                    [
                        'reference' => 'E12345678912,E23456789123',
                        'type' => 'K2',
                    ],
                    [
                        'reference' => 'CIF',
                    ],
                ],
                'supplier' => [
                    'name' => 'Very Good Supplier Sdn Bhd',
                    'email' => 'very_good@gmail.com',
                    'phone' => '+6038875334',
                    'tin' => 'SG234872894234',
                    'brn' => 'OS2345872984234234',
                    'sst' => '2348972398472984',
                    'bank_account_number' => '234923984239423',
                    'address_line_1' => 'Block 40-4, Faber Ria Condominium',
                    'address_line_2' => 'Jalan Desa Sentosa',
                    'address_line_3' => 'Taman Desa',
                    'postcode' => '58100',
                    'city' => 'Kuala Lumpur',
                    'state' => $this->getCode('state_codes', 'Kuala Lumpur'),
                    'country' => $this->getCode('country_codes', 'Malaysia'),
                ],
                'buyer' => [
                    'name' => 'Wong Kim Fook',
                    'email' => 'fook@gmail.com',
                    'phone' => '+60128877553',
                    'tin' => 'UU28934723894723894',
                    'address_line_1' => '29, Jalan CJ4/15-3B',
                    'address_line_2' => 'Taman Cheras Jaya',
                    'postcode' => '43200',
                    'city' => 'Cheras',
                    'state' => $this->getCode('state_codes', 'Selangor'),
                    'country' => $this->getCode('country_codes', 'Malaysia'),
                ],
                'shipping' => [
                    'name' => 'Wong Kim Fook',
                    'tin' => 'UU28934723894723894',
                    'address_line_1' => '29, Jalan CJ4/15-3B',
                    'address_line_2' => 'Taman Cheras Jaya',
                    'postcode' => '43200',
                    'city' => 'Cheras',
                    'state' => $this->getCode('state_codes', 'Selangor'),
                    'country' => $this->getCode('country_codes', 'Malaysia'),
                ],
                'charges' => [
                    [
                        'amount' => 20,
                        'description' => 'Service Charge',
                    ],
                    [
                        'amount' => 30.50,
                        'description' => 'Labour Charge',
                    ],
                ],
                'discounts' => [
                    [
                        'amount' => 100,
                        'description' => 'Festival Discount',
                    ],
                ],
                'taxes' => [
                    [
                        'code' => $this->getCode('tax_types', 'Sales Tax'),
                        'name' => 'Sales Tax',
                        'amount' => 30,
                    ],
                ],
                'subtotal' => 500,
                'grand_total' => 530,
                'payable_total' => 530,
            ],
        ];
    }
}
