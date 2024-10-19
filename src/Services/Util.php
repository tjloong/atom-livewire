<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Number;
use Jiannius\Atom\Atom;

class Util
{
    public static function currency(
        $value = 0,
        $symbol = null,
        $rounding = false,
        $bracket = false,
        $short = false,
    ) : string
    {
        if (!is_numeric($value)) return $value;

        $value = (float) $value;

        if ($short) {
            $amount = static::short($value);
            $currency = $symbol ? "$symbol $amount" : $amount;
        }
        else {
            $amount = $rounding ? (round((float) $value * 2, 1)/2) : $value;
            $currency = $symbol ? ($symbol.' '.Number::format($amount, 2)) : Number::format($amount, 2);
        }

        return ($bracket && $value < 0) ? '('.str($currency)->replaceFirst('-', '').')' : $currency;
    }

    public static function uncurrency($value) : float
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $value);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $value);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

        return (float) str_replace(',', '.', $removedThousandSeparator);
    }

    public static function short($value, $locale = null) : string
    {
        if (!is_numeric($value)) return $value;

        $value = (float) $value;

        if ($value > 999999999) return round(($value/1000000000), 2).'B';
        if ($value > 999999) return round(($value/1000000), 2).'M';
        if ($value > 999) return round(($value/1000), 2).'K';
    
        return $value;
    }

    public static function address($value, $nl2br = true) : mixed
    {
        $l1 = get($value, 'address_1') ?? get($value, 'addr_1');
        if ($l1) $l1 = preg_replace('/,$/im', '', $l1);

        $l2 = get($value, 'address_2') ?? get($value, 'addr_2');
        if ($l2) $l2 = preg_replace('/,$/im', '', $l2);

        $zip = get($value, 'zip') ?? get($value, 'postcode');
        $city = get($value, 'city');
        $l3 = collect([$zip, $city])->filter()->join(' ');

        $state = get($value, 'state');
        $country = get($value, 'country');
        $country = Atom::country($country, 'name');
        $l4 = collect([$state, $country])->filter()->join(' ');

        $name = get($value, 'name');
        $company = get($value, 'company');
        $lines = collect([$l1, $l2, $l3, $l4])->filter()->join(', ');

        $address = collect([$name, $company, $lines])->filter();
        $address = $nl2br ? $address->join('<br>') : $address->join(', ');

        return empty($address) ? null : $address;
    }

    public static function filesize($value = 0, $unit = 'MB') : mixed
    {
        if (!is_numeric($value)) return $value;

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = array_search($unit, $units);

        while ($value > 1024) {
            $value = $value/1024;
            $index = $index + 1;
        }

        return round($value, 2).' '.$units[$index];
    }
}