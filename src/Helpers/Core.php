<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

/**
 * Get status color
 */
function get_status_color($status = null)
{
    $color = [
        'red' => 'red',
        'due' => 'red',
        'error' => 'red',
        'failed' => 'red',

        'yellow' => 'yellow',
        'new' => 'yellow',
        'sent' => 'yellow',
        'admin' => 'yellow',
        'unpaid' => 'yellow',
        'opened' => 'yellow',
        'upcoming' => 'yellow',
        'queueing' => 'yellow',
        'submitted' => 'yellow',
        'checked-out' => 'yellow',

        'blue' => 'blue',
        'ready' => 'blue',
        'default' => 'blue',
        'partial' => 'blue',
        'pending' => 'blue',
        'shipped' => 'blue',
        'feedback' => 'blue',
        'processing' => 'blue',

        'green' => 'green',
        'paid' => 'green',
        'show' => 'green',
        'active' => 'green',
        'billed' => 'green',
        'closed' => 'green',
        'success' => 'green',
        'invoiced' => 'green',
        'verified' => 'green',
        'certified' => 'green',
        'delivered' => 'green',
        'published' => 'green',
        'completed' => 'green',
        'onboarded' => 'green',
        'offered' => 'green',
        'accepted' => 'green',
        'available' => 'green',

        'black' => 'black',
        'blocked' => 'black',
        'trashed' => 'black',
        'voided' => 'black',
    ][$status] ?? null;

    return [
        'green' => [
            'text' => 'text-green-800',
            'bg' => 'bg-green-100',
            'border' => 'border border-green-200',
        ],
        'red' => [
            'text' => 'text-red-800',
            'bg' => 'bg-red-100',
            'border' => 'border border-red-200',
        ],
        'blue' => [
            'text' => 'text-blue-800',
            'bg' => 'bg-blue-100',
            'border' => 'border border-blue-200',
        ],
        'yellow' => [
            'text' => 'text-yellow-800',
            'bg' => 'bg-yellow-100',
            'border' => 'border border-yellow-200',
        ],
        'indigo' => [
            'text' => 'text-indigo-800',
            'bg' => 'bg-indigo-100',
            'border' => 'border border-indigo-200',
        ],
        'orange' => [
            'text' => 'text-orange-800',
            'bg' => 'bg-orange-100',
            'border' => 'border border-orange-200',
        ],
        'black' => [
            'text' => 'text-white',
            'bg' => 'bg-black',
            'border' => null,
        ],
        'gray' => [
            'text' => 'text-gray-800',
            'bg' => 'bg-gray-100',
            'border' => null,
        ],
    ][$color ?? 'gray'];
}

/**
 * Recaptcha verification
 */
function verify_recaptcha($token, $secret = null)
{
    $secret = $secret ?? settings('recaptcha_secret') ?? env('RECAPTCHA_SECRET');

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
 * Flatten tabs array
 */
function tabs($tabs, $slug = false)
{
    $col = collect();

    foreach ($tabs as $tab) {
        if ($children = data_get($tab, 'dropdown') ?? data_get($tab, 'tabs') ?? data_get($tab, 'children')) {
            foreach ($children as $child) {
                $col->push($child);
            }
        }
        else $col->push($tab);
    }

    return $slug !== false
        ? $col->firstWhere('slug', $slug)
        : $col->filter(fn($col) => isset($col['slug']))->values();
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
 * Tenant
 */
function tenant($attr = null, $default = null, $tenant = null)
{
    if (!enabled_module('tenants')) return;

    return model('tenant')->retrieve($attr, $default, $tenant);
}

/**
 * Settings
 */
function settings($attr = null, $default = null)
{
    if (config('atom.static_site')) return $default;

    $settings = model('site_setting')->generate();

    if (!$attr) {
        return $settings;
    }
    else if (is_string($attr)) {
        return data_get($settings, $attr, $default);
    }
    else if (is_array($attr)) {
        foreach ($attr as $key => $val) {
            $setting = model('site_setting')->where('name', $key)->first();
            $setting->fill(['value' => $val])->save();
        }
    }
}

/**
 * Export data to pdf
 */
function pdf($view, $data)
{
    return Pdf::loadView($view, $data);
}

/**
 * Export to word
 */
function word($pages, $config = [])
{
    $phpword = new \PhpOffice\PhpWord\PhpWord();

    $phpword->addTitleStyle(
        data_get($config, 'title_style.depth', 1), 
        data_get($config, 'title_style.font', ['size' => 14, 'bold' => true]), 
        data_get($config, 'title_style.paragraph', ['spaceAfter' => 240]),
    );

    $section = $phpword->addSection();

    foreach ($pages as $i => $page) {
        if ($i > 0) $section->addPageBreak();

        if ($title = data_get($page, 'title')) {
            $section->addTitle(
                htmlspecialchars(is_string($title) ? $title : data_get($title, 'text')),
                data_get($title, 'depth', 1),
            );
        }

        foreach (data_get($page, 'lines') as $line) {
            if (is_null($line)) $section->addTextBreak();
            else if (is_string($line)) $section->addText(htmlspecialchars($line));
            else {
                $section->addText(
                    htmlspecialchars(data_get($line, 'text')), 
                    data_get($line, 'font'),
                    data_get($line, 'paragraph'),
                );
            }
        }
    }

    $dir = storage_path('export');
    if (!File::exists($dir)) File::makeDirectory($dir);

    $filename = str()->finish(
        data_get($config, 'filename', 'word-export-'.time()),
        '.docx'
    );

    $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
    $writer->save($dir.'/'.$filename);

    return redirect()->route('__export', [$filename]);

}

/**
 * Export collection to excel
 */
function excel($collection)
{
    return (new FastExcel($collection));
}

/**
 * Export to excel sheets
 */
function excelsheets($sheets, $config = [], $iterator = null)
{
    $dir = storage_path('export');
    if (!File::exists($dir)) File::makeDirectory($dir);

    $sheets = new SheetCollection($sheets);
    $filename = str()->finish(
        data_get($config, 'filename', 'sheets-export-'.time()),
        '.xlsx'
    );

    $fastexcel = (new FastExcel($sheets));

    if (!data_get($config, 'header', true)) $fastexcel->withoutHeaders();
    
    $fastexcel->export($dir.'/'.$filename, $iterator);

    return redirect()->route('__export', [$filename]);
}

/**
 * Set the SEO
 */
function seo($seo)
{
    if ($title = data_get($seo, 'title')) config(['atom.seo.title' => $title]);
    if ($description = data_get($seo, 'description')) config(['atom.seo.description' => $description]);
    if ($image = data_get($seo, 'image')) config(['atom.seo.image' => $image]);
    if ($hreflang = data_get($seo, 'hreflang')) config(['atom.seo.hreflang' => $hreflang]);
    if ($canonical = data_get($seo, 'canonical')) config(['atom.seo.canonical' => $canonical]);
    if ($jsonld = data_get($seo, 'jsonld')) config(['atom.seo.jsonld' => $jsonld]);
}

/**
 * Make model instance
 */
function model($name)
{
    $name = str($name)->singular()->studly()->toString();

    $class = collect([
        'App\\Models\\'.$name,
        'Jiannius\\Atom\\Models\\'.$name,
    ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

    return app($class);
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

    return collect($module)->filter(fn($val) => in_array($val, settings('modules')))->count() > 0;
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
    if (!is_string($url)) return;
    
    $regex = '/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/';

    preg_match($regex, $url, $matches);

    return collect($matches)->first();
}

/**
 * Short number
 */
function short_number($n, $locale = null)
{
    if ($n > 999999999) return round(($n/1000000000), 2).'B';
    if ($n > 999999) return round(($n/1000000), 2).'M';
    if ($n > 999) return round(($n/1000), 2).'K';

    return $n;
}

/**
 * Get countries
 */
function countries($key = false)
{
    $path = collect([
        resource_path('json/countries.json'),
        __DIR__.'/../../resources/json/countries.json',
    ])->first(fn($val) => file_exists($val));

    $json = json_decode(file_get_contents($path), true);
    
    $values = collect($json)->map(fn($val) => array_merge(
        ['code' => data_get($val, 'iso_code')],
        Arr::except($val, 'iso_code'),
    ));

    if ($key !== false) {
        $split = explode('.', $key);
        $name = $split[0];
        $field = $split[1] ?? null;
        $value = $values->first(fn($val) => 
            strtolower(data_get($val, 'code')) === strtolower($name)
            || strtolower(data_get($val, 'name')) === strtolower($name)
        );

        if ($value && $field) return data_get($value, $field);
        elseif ($value) return $value;
        else return null;
    }

    return $values;
}

/**
 * Get currencies
 */
function currencies($country = false)
{
    $currencies = countries()
        ->map(function ($val) {
            if ($currency = data_get($val, 'currency')) {
                return array_merge(
                    [
                        'country_name' => data_get($val, 'name'),
                        'country_code' => data_get($val, 'code'),
                    ],
                    $currency,
                );
            }

            return null;
        })
        ->filter()
        ->values();

    if ($country !== false) {
        $split = explode('.', $country);
        $name = $split[0];
        $field = $split[1] ?? null;
        $value = $currencies->first(fn($val) => 
            strtolower(data_get($val, 'country_code')) === strtolower($name)
            || strtolower(data_get($val, 'country_name')) === strtolower($name)
        );

        if ($value && $field) return data_get($value, $field);
        elseif ($value) return $value;
    }

    return $currencies;
}

/**
 * Format number to currency
 *
 * @return string
 */
function currency($num, $symbol = null, $rounded = true, $bracket = true)
{
    if (!is_numeric($num)) return $num;

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
 * Check authenticated user tier
 */
function tier($name)
{
    return user() && user()->isTier($name);
}

/**
 * Get authenticated user
 */
function user($attr = null)
{
    $user = auth()->user();
    if (!$user) return;
    if (!$attr) return $user;

    if (
        str($attr)->is('signup') 
        || str($attr)->is('signup.*')
        || str($attr)->is('pref') 
        || str($attr)->is('pref.*')
    ) {
        $value = data_get($user, 'data.'.$attr);

        if ($attr === 'pref.timezone' && !$value) return config('atom.timezone');
        else return $value ? json_decode(json_encode($value), true) : null;
    }
    else if ($attr) return data_get($user, $attr);
}

/**
 * Get current user
 */
function role($name)
{
    if (!user()) return;

    return user()->isRole($name);
}

/**
 * Format filesize
 */
function format_filesize($value, $initUnit = 'B')
{
    if (!is_numeric($value)) return $value;

    $n = $value;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $index = array_search($initUnit, $units);

    while ($n > 1024) {
        $n = $n/1024;
        $index = $index + 1;
    }

    return round($n, 2).' '.$units[$index];
}

/**
 * Format address
 */
function format_address($value)
{
    $l1 = preg_replace('/,$/im', '', data_get($value, 'address_1'));
    $l2 = preg_replace('/,$/im', '', data_get($value, 'address_2'));
    $l3 = collect([data_get($value, 'zip'), data_get($value, 'postcode'), data_get($value, 'city')])->filter()->join(' ');
    $l4 = collect([
        data_get($value, 'state'), 
        data_get(metadata()->countries(data_get($value, 'country')), 'name'),
    ])->filter()->join(' ');

    $address = collect([$l1, $l2, $l3, $l4])->filter()->join(', ');

    return empty($address) ? null : $address;
}

/**
 * Format date to string
 */
function format_date($date, $format = 'date', $tz = null): mixed
{
    if (!$date) return $date;

    if (!$date instanceof Carbon) {
        if (validator(['date_value' => $date], ['date_value' => 'date'])->fails()) return $date;
        $date = Carbon::parse($date);
    }

    $tz = $tz ?? user('pref.timezone') ?? config('atom.timezone');
    $date = $date->timezone($tz);

    if ($format === 'carbon') return $date;
    else if ($format === 'date') return $date->format('d M Y');
    else if ($format === 'datetime') return $date->format('d M Y g:iA');
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