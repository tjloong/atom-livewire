<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Facades\DB;
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

    public static function short($value = 0, $locale = null) : string
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

    public static function daterange($range) : array
    {
        $range = $range ?? '1970-01-01 00:00:00 to '.now()->toDateTimeString();

        $from = head(explode('to', $range));
        $from = carbon($from ?: '1970-01-01 00:00:00');

        $to = last(explode('to', $range));
        $to = $to ? carbon($to) : now();

        $diff = [
            'd' => $from->diffInDays($to),
            'm' => $from->diffInMonths($to),
            'y' => $from->diffInYears($to),
        ];

        $past = get($diff, 'd') > 0 ? [
            'from' => $from->copy()->subDays(get($diff, 'd')),
            'to' => $to->copy()->subDays(get($diff, 'd')),
        ] : null;

        $tz = now()->timezone(
            optional(user())->settings('timezone') ?? config('atom.timezone')
        )->format('P');

        return [
            'range' => $range,
            'from' => $from,
            'to' => $to,
            'diff' => $diff,
            'past' => $past,
            'tz' => $tz,
        ];
    }

    public static function queryBreakdown(
        $query,
        $diff,
        $start,
        $dateColumn = 'date',
        $totalColumn = 'total'
    ) : mixed
    {
        $breakdown = collect();

        if ($diff['y'] > 1) {
            $grouped = DB::query()->fromSub($query, 'data')
                ->selectRaw("
                    year(`$dateColumn`) AS `year`,
                    sum(`$totalColumn`) AS `total`
                ")
                ->groupBy('year')
                ->get();

            foreach (range(0, $diff['y']) as $n) {
                $carbon = $start->copy()->local()->addYears($n);
                $label = $carbon->year;
                $value = $grouped->where('year', $carbon->year)->first();
                $breakdown->put($label, get($value, 'total', 0));
            }
        }
        else if ($diff['m'] > 1) {
            $grouped = DB::query()->fromSub($query, 'data')
                ->selectRaw("
                    date_format(`$dateColumn`, \"%c\") AS `month`,
                    year(`$dateColumn`) AS `year`,
                    sum(`$totalColumn`) AS `total`
                ")
                ->groupBy(['month', 'year'])
                ->get();

            foreach (range(0, $diff['m']) as $n) {
                $carbon = $start->copy()->local()->addMonths($n);
                $label = $carbon->shortEnglishMonth.' '.$carbon->format('y');
                $value = $grouped->where('month', $carbon->month)->where('year', $carbon->year)->first();
                $breakdown->put($label, get($value, 'total', 0));
            }
        }
        else {
            $grouped = DB::query()->fromSub($query, 'data')
                ->selectRaw("
                    day(`$dateColumn`) as `day`,
                    date_format(`$dateColumn`, \"%c\") AS `month`,
                    sum(`$totalColumn`) as `total`
                ")
                ->groupBy(['day', 'month'])
                ->get();

            foreach (range(0, $diff['d']) as $n) {
                $carbon = $start->copy()->local()->addDays($n);
                $label = $carbon->day;
                $value = $grouped->where('month', $carbon->month)->where('day', $carbon->day)->first();
                $breakdown->put($label, get($value, 'total', 0));
            }
        }

        return $breakdown;
    }

    public static function getYoutubeVideoId($value)
    {
        $regex = '/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/';
    
        preg_match($regex, $value, $matches);
    
        return collect($matches)->first();    
    }

    public static function getYoutubeEmbedUrl($value)
    {
        $vid = self::getYoutubeVideoId($value);

        return 'https://www.youtube.com/embed/'.$vid;
    }

    public static function getYoutubeVideoInfo($value) : array
    {
        $vid = self::getYoutubeVideoId($value);
        $info = json_decode(file_get_contents('https://noembed.com/embed?dataType=json&url='.$value), true);

        return [
            ...$info,
            'embed_url' => $vid ? 'https://www.youtube.com/embed/'.$vid : null,
        ];
    }
}
