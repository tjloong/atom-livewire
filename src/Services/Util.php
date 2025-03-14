<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Facades\DB;
use Jiannius\Atom\Atom;

class Util
{
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
