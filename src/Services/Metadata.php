<?php

namespace Jiannius\Atom\Services;

class Metadata
{
    /**
     * Get countries
     */
    public function countries($code = null) {
        $path = __DIR__.'/../../resources/json/countries.json';
        $json = json_decode(file_get_contents($path));
        $countries = collect($json);

        if ($code) return $countries->firstWhere('iso_code', $code) ?? $countries->firstWhere('name', $code);

        return $countries;
    }

    /**
     * Get states for country
     */
    public function states($country)
    {
        $states = optional($this->countries($country))->states ?? [];

        return collect($states)->sortBy('code');
    }

    /**
     * Get locales
     */
    public function locales($code = null)
    {
        $locales = collect(json_decode(json_encode([
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'ms', 'name' => 'Bahasa Melayu'],
            ['code' => 'zh', 'name' => '中文'],
        ])));

        return $code ? $locales->where('code', $code)->first() : $locales;
    }

    /**
     * Get payment methods
     */
    public function paymentMethods($code = null)
    {
        $paymentMethods = collect([
            ['code' => 'cash', 'label' => 'Cash'],
            ['code' => 'master', 'label' => 'Master Card'],
            ['code' => 'visa', 'label' => 'Visa Card'],
            ['code' => 'amex', 'label' => 'AMEX Card'],
            ['code' => 'bank-transfer', 'label' => 'Bank-Transfer'],
            ['code' => 'tng', 'label' => 'Touch & Go'],
            ['code' => 'grabpay', 'label' => 'GrabPay'],
            ['code' => 'boost', 'label' => 'Boost'],
        ]);

        return $code ? $paymentMethods->firstWhere('code', $code) : $paymentMethods;
    }
}