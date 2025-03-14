<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Number as NumberHelper;

class Number
{
    public static function currency(
        $value = 0,
        $symbol = null,
        $rounding = false,
        $bracket = false,
        $short = false,
    ) : string
    {
        if (!is_numeric($value)) return $value ?? '';

        $value = (float) $value;

        if ($short) {
            $amount = static::short($value);
            $currency = $symbol ? "$symbol $amount" : $amount;
        }
        else {
            $amount = $rounding ? (round((float) $value * 2, 1)/2) : $value;
            $currency = $symbol ? ($symbol.' '.NumberHelper::format($amount, 2)) : NumberHelper::format($amount, 2);
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

    public static function short($value = 0, $locale = null) : string
    {
        if (!is_numeric($value)) return $value;

        $value = (float) $value;

        if ($value > 999999999) return round(($value/1000000000), 2).'B';
        if ($value > 999999) return round(($value/1000000), 2).'M';
        if ($value > 999) return round(($value/1000), 2).'K';
    
        return $value;
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