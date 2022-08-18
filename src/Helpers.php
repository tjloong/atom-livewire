<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Recaptcha verification
 */
function verify_recaptcha($token, $secret = null)
{
    $secret = $secret ?? env('RECAPTCHA_SECRET') ?? site_settings('recaptcha_secret');

	$data = http_build_query([
		'secret' => $secret,
		'response' => $token,
		'remoteip' => $_SERVER['REMOTE_ADDR'],
	]);

	$opts = ['http' => [
		'method' => 'POST',
		'header' => 'Content-type: application/x-www-form-urlencoded',
		'content' => $data,
	]];

	$context  = stream_context_create($opts);
	$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
	$result = json_decode($response);

	return $result->success;
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
 * Account settings
 */
function account_settings($name, $default = null)
{
    if (
        config('atom.static_site')
        || !auth()->user()
        || !auth()->user()->account
    ) return $default;

    $settings = auth()->user()->account->accountSettings;

    if (is_string($name)) return data_get($settings, $name, $default);
    else $settings->fill($name)->save();
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

    return rescue(function() use ($module) {
        $enabled = collect(json_decode(
            data_get(DB::table('site_settings')->where('name', 'modules')->first(), 'value')
        ));

        return $enabled->contains($module);
    }, false);
}

/**
 * Define route
 */
function define_route($path = null, $action = null, $method = 'get')
{
    if (!$path && !$action) return app('router');
    if (is_callable($action)) return app('router')->$method($path, $action);

    // controller
    if (str()->is('*Controller@*', $action)) {
        $postfix = substr($action, 0, strpos($action, '@'));
        $classmethod = str_replace('@', '', substr($action, strpos($action, '@'), strlen($action)));
        $class = collect([
            'App\Http\Controllers\\'.$postfix,
            'Jiannius\Atom\Http\Controllers\\'.$postfix,
        ])->first(fn($val) => class_exists($val));

        return app('router')->$method($path, [$class, $classmethod]);
    }
    // livewire
    else {
        $class = collect([
            'App\Http\Livewire\\'.$action,
            'App\Http\Livewire\\'.$action.'\Index',
            'Jiannius\Atom\Http\Livewire\\'.$action,
            'Jiannius\Atom\Http\Livewire\\'.$action.'\Index',
        ])->first(fn($val) => class_exists($val));

        return app('router')->$method($path, $class);
    }
}

/**
 * Get current route
 * 
 * @return string
 */
function current_route($name = null)
{
    $route = request()->route()->getName();

    if (is_string($name)) return str($route)->is($name);
    elseif (is_array($name)) {
        return is_numeric(
            collect($name)->search(fn($val) => str($route)->is($val))
        );
    }

    return $route;
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

    return collect($matches)->first();
}

/**
 * Format number to currency
 *
 * @return string
 */
function currency($num, $symbol = null, $rounded = true, $bracket = true)
{
    $num = (float)$num ?: 0;
    $round = $rounded ? (round($num * 2, 1)/2) : $num;
    $currency = number_format($round, 2);

    if ($symbol) $currency = "$symbol $currency";
    if ($bracket && $num < 0) $currency = '(' . str_replace('-', '', $currency) . ')';

    return $currency;
}

/**
 * Format currency string to number
 */
function uncurrency($string)
{
    $cleanString = preg_replace('/([^0-9\.,])/i', '', $string);
    $onlyNumbersString = preg_replace('/([^0-9])/i', '', $string);

    $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

    $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
    $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

    return (float) str_replace(',', '.', $removedThousandSeparator);
}

/**
 * Get timezone
 */
function timezone()
{
    if ($account = optional(auth()->user())->account) {
        if ($tz = $account->accountSettings->timezone ?? null) {
            return $tz;
        }
    }

    return config('atom.timezone');
}

/**
 * Format date to string
 * 
 * @return string
 */
function format_date($date, $format = 'date', $tz = null)
{
    $tz = $tz ?? timezone();

    if (!$date) return $date;
    if (!$date instanceof Carbon) $date = Carbon::parse($date);

    $date = $date->timezone($tz);

    if ($format === 'carbon') return $date;
    else if ($format === 'date') return $date->format('d M, Y');
    else if ($format === 'datetime') return $date->format('d M, Y g:iA');
    else if ($format === 'time') return $date->format('g:i A');
    else if ($format === 'time-full') return $date->format('g:i:s A');
    else if ($format === 'human') return $date->diffForHumans();
    else if ($format) return $date->format($format);
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
    $content = preg_replace('/[^A-Za-z0-9\,\.\’\‘\";?]/', ' ', $content);
    $content = preg_replace('/ +/', ' ', $content);
    $content = trim($content);
    $length = str()->length($content);

    if ($length > 100) return str()->limit($content, 100);
    else return $content;
}