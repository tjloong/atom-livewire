<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\FastExcel\FastExcel;

function export_to_excel($filename, $collection, $iterator = null)
{
    $dir = storage_path('export');

    if (!File::exists($dir)) File::makeDirectory($dir);

    (new FastExcel($collection))->export($dir . '/' . $filename, $iterator);

    return redirect()->route('__export', [$filename]);
}

/**
 * Get model class name
 */
function get_model_class_name($name)
{
    return config('atom.models.' . $name) ?? 'Jiannius\\Atom\\Models\\' . $name;
}

/**
 * Get model instance
 */
function get_model($name)
{
    return app(get_model_class_name($name));
}

/**
 * Check has feature
 * 
 * @return boolean
 */
function enabled_feature($feature)
{
    $value = config('atom.features.' . $feature);

    if (is_string($value) || is_array($value)) return !empty($value);
    else return $value;
}

/**
 * Get current route
 * 
 * @return string
 */
function current_route()
{
    return request()->route()->getName();
}

/**
 * Get current locale url
 */
function locale_url($url)
{
    $locale = app()->currentLocale();

    if ($locale === 'en') return $url;
    else return '/' . $locale . str_replace($locale, '', $url);
}

/**
 * Format number to currency
 *
 * @return string
 */
function currency($num, $symbol = null, $bracket = true)
{
    $num = (float)$num ?: 0;
    $currency = number_format($num, 2);

    if ($symbol) $currency = "$symbol $currency";
    if ($bracket && $num < 0) $currency = '(' . str_replace('-', '', $currency) . ')';

    return $currency;
}

/**
 * Format date to string
 * 
 * @return string
 */
function format_date($date, $format = 'date', $tz = null)
{
    if (!$date) return $date;
    if (!$date instanceof Carbon) $date = Carbon::parse($date);

    $tz = $tz ?? config('atom.timezone');

    if ($format === 'date') return $date->timezone($tz)->format('d M, Y');
    else if ($format === 'datetime') return $date->timezone($tz)->format('d M, Y g:iA');
    else if ($format === 'time') return $date->timezone($tz)->format('g:i A');
    else if ($format === 'time-full') return $date->timezone($tz)->format('g:i:s A');
    else if ($format === 'human') return $date->timezone($tz)->diffForHumans();
}

/**
 * Parse date range from string
 *
 * @param string $str
 * @return object
 */
function date_range($from, $to, $tz = 'UTC')
{
    if (!$from instanceof Carbon) $from = Carbon::createFromFormat($from, 'Y-m-d H:i:s');
    if (!$to instanceof Carbon) $to = Carbon::createFromFormat($to, 'Y-m-d H:i:s');

    return (object)[
        'from' => (object)[
            'date' => $from->copy()->setTimeZone($tz)->toDateString(),
            'datetime' => $from->copy()->setTimeZone($tz)->toDatetimeString(),
            'carbon' => $from->copy()->setTimezone($tz),
        ],
        'to' => (object)[
            'date' => $to->copy()->setTimeZone($tz)->toDateString(),
            'datetime' => $to->copy()->setTimeZone($tz)->toDatetimeString(),
            'carbon' => $to->copy()->setTimezone($tz),
        ],
        'diffInDays' => $from->copy()->diffInDays($to),
        'diffInMonths' => $from->copy()->diffInMonths($to->copy()->endOfMonth()),
        'diffInYears' => $from->copy()->diffInYears($to->copy()->endOfYear()),
    ];
}

/**
 * Replace a given string within a given file.
 *
 * @param  string  $search
 * @param  string  $replace
 * @param  string  $path
 * @return void
 */
function replace_in_file($search, $replace, $path)
{
    file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
}

/**
 * Get excerpt from html
 * 
 * @return string
 */
function html_excerpt($html)
{
    $content = $html;
    $content = strip_tags($content);
    $content = html_entity_decode($content);
    $content = urldecode($content);
    $content = preg_replace('/[^A-Za-z0-9]/', ' ', $content);
    $content = preg_replace('/ +/', ' ', $content);
    $content = trim($content);
    $length = Str::length($content);

    if ($length > 120) return Str::limit($content, 120);
    else return $content;
}