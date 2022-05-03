<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Get app route
 */
function app_route()
{
    $route = null;

    if ($user = auth()->user()) $route = $user->home();
    if (!$route) $route = Route::has('app.home') ? route('app.home') : route('page');

    return $route;
}

/**
 * Get livewire component name
 */
function livewire_name($path = null)
{
    if (!$path) return;

    $dotted = implode('.', explode('/', $path));
    $slashed = collect(explode('/', $path))->map(fn($str) => str()->studly($str))->filter()->join('\\');

    if (class_exists('App\\Http\\Livewire\\'.$slashed)) return $dotted;
    else if (class_exists('App\\Http\\Livewire\\'.$slashed.'\\Index')) return $dotted.'.index';
    else if (class_exists('Jiannius\\Atom\\Http\\Livewire\\'.$slashed)) return "atom.{$dotted}";
    else if (class_exists('Jiannius\\Atom\\Http\\Livewire\\'.$slashed.'\\Index')) return "atom.{$dotted}.index";
}

/**
 * Metadata
 */
function metadata($method = null, $args = null)
{
    $classes = [
        'try' => 'App\\Services\\Metadata',
        'use' => 'Jiannius\\Atom\\Services\\Metadata',
    ];

    $class = class_exists($classes['try'])
        ? $classes['try']
        : $classes['use'];

    $instance = app($class);

    if ($method) {
        $methodName = str()->camel($method);
        return $instance->$methodName($args);
    }
    else return $instance;
}

/**
 * Breadcrumbs
 */
function breadcrumbs()
{
    $classes = [
        'try' => 'App\\Services\\Breadcrumbs',
        'use' => 'Jiannius\\Atom\\Services\\Breadcrumbs',
    ];

    $class = class_exists($classes['try'])
        ? $classes['try']
        : $classes['use'];

    return app($class);
}

/**
 * Get tabs array from atom config
 */
function get_tabs_from_config($config)
{
    return collect(config($config, []))
        ->map(fn($val, $key) => is_array($val)
            ? ['group' => $key, 'tabs' => collect($val)->map(fn($subval, $subkey) => ['value' => $subkey, 'label' => $subval])]
            : ['value' => $key, 'label' => $val]
        )
        ->values();
}

/**
 * Site settings
 */
function site_settings($name, $default = null)
{
    if (config('atom.static_site')) return $default;

    if (is_string($name)) return model('site_setting')->getSetting($name) ?? $default;
    else {
        foreach($name as $key => $val) {
            model('site_setting')->setSetting($key, $val);
        }
    }
}

/**
 * Export collection to excel
 */
function export_to_excel($filename, $collection, $iterator = null)
{
    $dir = storage_path('export');

    if (!File::exists($dir)) File::makeDirectory($dir);

    (new FastExcel($collection))->export($dir . '/' . $filename, $iterator);

    return redirect()->route('__export', [$filename]);
}

/**
 * Make model instance
 * 
 * @param string $name
 * @param boolean $instance
 */
function model($name)
{
    $name = str()->studly($name);
    $try = 'App\\Models\\'.$name;
    $fallback = 'Jiannius\\Atom\\Models\\'.$name;
    $classname = class_exists($try) ? $try : $fallback;

    return app($classname);
}

/**
 * Check module is enabled
 * 
 * @return boolean
 */
function enabled_module($module)
{
    if (config('atom.static_site')) return false;
    if (app()->runningInConsole()) return true;

    $enabled = collect(json_decode(DB::table('site_settings')->where('name', 'modules')->first()->value));

    return $enabled->contains($module);
}

/**
 * Abort module error
 */
function abort_module($module)
{
    abort(500, str()->headline($module) . ' module is not enabled.');
}

/**
 * Define route
 */
function define_route($path = null, $action = null, $method = 'get')
{
    if (!$path && !$action) return app('router');
    if (is_callable($action)) return app('router')->$method($path, $action);

    $isController = str()->is('*Controller@*', $action);
    $namespacePrefix = $isController ? 'Jiannius\\Atom\\Http\\Controllers\\' : 'Jiannius\\Atom\\Http\\Livewire\\';
    $tryNamespacePrefix = $isController ? 'App\\Http\\Controllers\\' : 'App\\Http\\Livewire\\';
    $className = $isController ? substr($action, 0, strpos($action, '@')) : $action;
    $classMethod = $isController ? str_replace('@', '', substr($action, strpos($action, '@'), strlen($action))) : null;
    $fullClass = class_exists($tryNamespacePrefix . $className)
        ? $tryNamespacePrefix . $className
        : $namespacePrefix . $className;

    return app('router')->$method(
        $path, 
        $classMethod ? [$fullClass, $classMethod] : $fullClass
    );
}

/**
 * Get current route
 * 
 * @return string
 */
function current_route($name = null)
{
    $route = request()->route()->getName();

    if ($name) return str($route)->is($name);

    return $route;
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
 * Mask email address
 */
function mask_email($email)
{
    $split = explode('@', $email);
    $head = str($split[0])->mask('*', 1, strlen($split[0]) - 2);
    $tailsplit = explode('.', $split[1]);
    $tailhead = array_shift($tailsplit);
    $tail = implode('.', array_merge([str($tailhead)->mask('*', 1)], $tailsplit));

    return implode('@', [$head, $tail]);
}

/**
 * Get youtube vid
 */
function youtube_vid($url)
{
    $regex = '/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/';

    preg_match($regex, $url, $matches);

    return $matches[0];
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
    $tz = $tz ?? config('atom.timezone');

    if (!$date) return $date;
    if (!$date instanceof Carbon) $date = Carbon::parse($date);

    $date = $date->timezone($tz);

    if ($format === 'carbon') return $date;
    else if ($format === 'date') return $date->format('d M, Y');
    else if ($format === 'datetime') return $date->format('d M, Y g:iA');
    else if ($format === 'time') return $date->format('g:i A');
    else if ($format === 'time-full') return $date->format('g:i:s A');
    else if ($format === 'human') return $date->diffForHumans();
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
    $content = preg_replace('/[^A-Za-z0-9\,\.\’\‘\";]/', ' ', $content);
    $content = preg_replace('/ +/', ' ', $content);
    $content = trim($content);
    $length = str()->length($content);

    if ($length > 120) return str()->limit($content, 120);
    else return $content;
}