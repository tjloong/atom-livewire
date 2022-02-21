<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Set breadcrumbs home
 */
function breadcrumb_home($label)
{
    $url = url()->current();
    $route = current_route();
    $crumb = array_merge(compact('label', 'url', 'route'), parse_url($url));

    session(['breadcrumbs' => [$crumb]]);
}

/**
 * Add entry to breadcrumbs
 */
function breadcrumb($label)
{
    if ($label === false) session()->forget('breadcrumbs');
    else {
        $url = rtrim(url()->current(), '/');
        $route = current_route();
        $crumb = array_merge(compact('label', 'url', 'route'), parse_url($url));
        $crumbs = collect(session('breadcrumbs'));
        $exists = $crumbs
            ->filter(fn($val) => $val['label'] === $crumb['label'] && $val['route'] === $crumb['route'])
            ->count() > 0;

        if ($exists) {
            $index = $crumbs->search(fn($val) => $val['label'] === $crumb['label'] && $val['route'] === $crumb['route']);
            $crumbs = $crumbs->reject(fn($val, $key) => $key > $index);
        }
        else {
            $crumbs->push($crumb);
        }

        $newCrumbs = $crumbs->values()->all();
    
        // update previous url if it's been changed
        $prevIndex = array_key_last($newCrumbs) - 1;
        $prevCrumb = $newCrumbs[$prevIndex];
        $prevLatestUrl = rtrim(url()->previous(), '/');
        $prevLatestInfo = parse_url($prevLatestUrl);

        if ($prevLatestUrl !== $prevCrumb['url'] && $prevLatestInfo['path'] === $prevCrumb['path']) {
            $prevCrumb = array_merge($prevCrumb, array_merge(
                ['url' => $prevLatestUrl],
                $prevLatestInfo
            ));
    
            $newCrumbs[$prevIndex] = $prevCrumb;
        }

        session(['breadcrumbs' => $newCrumbs]);
    }
}

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
 * Define route
 */
function define_route($path, $action, $name, $method = 'get')
{
    $isController = Str::is('*Controller@*', $action);
    $namespacePrefix = $isController ? 'Jiannius\\Atom\\Http\\Controllers\\' : 'Jiannius\\Atom\\Http\\Livewire\\';
    $tryNamespacePrefix = $isController ? 'App\\Http\\Controllers\\' : 'App\\Http\\Livewire\\';
    $className = $isController ? substr($action, 0, strpos($action, '@')) : $action;
    $classMethod = $isController ? str_replace('@', '', substr($action, strpos($action, '@'), strlen($action))) : null;
    $fullClass = class_exists($tryNamespacePrefix . $className)
        ? $tryNamespacePrefix . $className
        : $namespacePrefix . $className;

    Route::$method(
        $path, 
        $classMethod ? [$fullClass, $classMethod] : $fullClass
    )->name($name);
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