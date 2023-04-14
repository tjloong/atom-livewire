<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

/**
 * Component ID
 */
function component_id($attributes, $default = null)
{
    if ($attributes->get('uuid') === true) return str()->uuid();
    if ($attributes->get('ulid') === true) return str()->ulid();

    if ($id = $attributes->get('id') ?? $attributes->get('uid') ?? null) {
        return $id;
    }

    if ($name = $attributes->wire('model')->value() ?? $attributes->get('name') ?? component_label($attributes) ?? null) {
        return str($name)->replace('.', '-')->slug()->toString();
    }

    return $default;
}

/**
 * Component label
 */
function component_label($attributes, $default = null, $trans = true)
{
    if ($attributes->get('label') === false) return null;
    
    $label = $attributes->get('label');

    if (!$label) {
        if ($name = $attributes->get('name') ?? $attributes->wire('model')->value() ?? null) {
            $last = last(explode('.', $name));
            $last = str($last)->is('*_id') ? str($last)->replaceLast('_id', '')->toString() : $last;
            $label = str($last)->headline()->toString();
        }
        else $label = $default;
    }

    return $trans ? __($label) : $label;
}

function component_error($errors, $attributes)
{
    if (!$errors) return false;

    $name = $attributes->get('name');
    $model = $attributes->wire('model')->value();

    return $errors->first($name ?? $model);
}

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
 * Get livewire component name
 */
function lw($name)
{
    $name = str()->replace('/', '.', $name);
    $segments = explode('.', $name);
    $last = last($segments);
    $slashed = collect($segments)->map(fn($str) => str()->studly($str))->filter()->join('\\');
    $class = collect([
        'App\Http\Livewire\\'.$slashed,
        'App\Http\Livewire\\'.$slashed.'\Index',
        'App\Http\Livewire\\'.$slashed.'\\'.str()->studly($last),
        'Jiannius\Atom\Http\Livewire\\'.$slashed,
        'Jiannius\Atom\Http\Livewire\\'.$slashed.'\Index',
        'Jiannius\Atom\Http\Livewire\\'.$slashed.'\\'.str()->studly($last),
    ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

    if ($class) {
        if (str($class)->startsWith('Jiannius\Atom')) return 'atom.'.$name;
        else return $name;
    }
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
        : $col->filter()->values();
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
function settings($name = null, $default = null)
{
    if (config('atom.static_site')) return $default;

    $settings = model('site_setting')->generate();

    if (!$name) {
        return $settings;
    }
    else if (is_string($name)) {
        return data_get($settings, $name, $default);
    }
    else if (is_array($name)) {
        foreach ($name as $key => $val) {
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
function excel($collection, $config = [], $iterator = null)
{
    $dir = storage_path('export');
    if (!File::exists($dir)) File::makeDirectory($dir);

    $filename = str()->finish(
        data_get($config, 'filename', 'excel-export-'.time()),
        '.xlsx'
    );

    $path = $dir.'/'.$filename;

    (new FastExcel($collection))->export($path, $iterator);

    return $path;
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
 * Make model instance
 * 
 * @param string $name
 * @param boolean $instance
 */
function model($name)
{
    $name = str()->studly($name);

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

    return rescue(function() use ($module) {
        $settings = session('settings') ?? model('site_setting')->generate();
        $modules = json_decode(data_get($settings, 'modules'));

        return collect($modules)->contains($module);
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
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        return app('router')->$method($path, [$class, $classmethod]);
    }
    // livewire
    else {
        $class = collect([
            'App\Http\Livewire\\'.$action,
            'App\Http\Livewire\\'.$action.'\Index',
            'Jiannius\Atom\Http\Livewire\\'.$action,
            'Jiannius\Atom\Http\Livewire\\'.$action.'\Index',
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

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
    $path = __DIR__.'/../resources/json/countries.json';
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
    }

    return $values;
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
 * 
 * @return string
 */
function format_date($date, $format = 'date', $tz = null)
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

/**
 * Get atom path
 */
function atom_path($path = null)
{
    return base_path('vendor/jiannius/atom-livewire'.($path ? '/'.$path : ''));
}

/**
 * Get atom class path
 */
function atom_ns_path($ns)
{
    $filepath = str($ns)->replace("\\", "/")->toString().'.php';

    if (str($filepath)->startsWith('App')) $filepath = app_path(str()->replaceFirst('App/', '', $filepath));
    if (str($filepath)->startsWith('Jiannius/Atom')) $filepath = atom_path('src/'.str()->replaceFirst('Jiannius/Atom/', '', $filepath));

    return $filepath;
}

/**
 * Get atom view
 */
function atom_view($name, $data = [])
{
    $path = str($name)->replace('.', '/');

    $view = collect([
        base_path('resources/views/livewire/'.$path) => 'livewire.'.$name,
        base_path('resources/views/livewire/'.$path.'/index') => 'livewire.'.$name.'.index',
        base_path('resources/views/'.$path) => $name,
        base_path('resources/views/'.$path.'/index') => $name.'.index',
        atom_path('resources/views/livewire/'.$path) => 'atom::livewire.'.$name,
        atom_path('resources/views/livewire/'.$path.'/index') => 'atom::livewire.'.$name.'.index',
        atom_path('resources/views/'.$path) => 'atom::'.$name,
        atom_path('resources/views/'.$path.'/index') => 'atom::'.$name.'.index',
    ])->first(fn($val, $key) => file_exists(str($key)->finish('.blade.php')));

    if ($view) {
        $main = head(explode('.', $name));

        if (file_exists(base_path('resources/views/layouts/'.$main.'.blade.php'))) {
            return view($view, $data)->layout('layouts.'.$main);
        }
        else return view($view, $data);
    }
}
