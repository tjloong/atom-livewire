<?php

if (!function_exists('find_class')) {
    function find_class($path)
    {
        $path = str($path)->replace('.', '\\')->replace('/', '\\');
        $path = collect(explode('\\', $path))->map(fn($val) => str()->studly($val))->join('\\');

        return collect([
            'App\\'.$path,
            'Jiannius\Atom\\'.$path,
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));
    }
}

if (!function_exists('find_livewire')) {
    function find_livewire($path) {
        $path = 'http.livewire.'.$path;

        return find_class($path) ?? find_class($path.'.index');
    }
}

if (!function_exists('atom_asset_path')) {
    function atom_asset_path($path = null, $relative = false)
    {
        return atom_path('public/build/assets/'.$path, $relative);
    }
}

/**
 * Get atom livewire component name
 */
function atom_lw($name)
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
 * Get atom path
 */
function atom_path($path = null, $relative = false)
{
    return $relative
        ? 'vendor/jiannius/atom-livewire'.($path ? '/'.$path : '')
        : base_path('vendor/jiannius/atom-livewire'.($path ? '/'.$path : ''));
}

/**
 * Get atom namespace path
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
