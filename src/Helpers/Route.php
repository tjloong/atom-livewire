<?php

use Illuminate\Support\Facades\Route;

// get current route
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

// get previous route
function previous_route($name = null)
{
    $route = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
    
    if (is_string($name)) return str($route)->is($name);
    elseif (is_array($name)) {
        return is_numeric(
            collect($name)->search(fn($val) => str($route)->is($val))
        );
    }

    return $route;
}

// check has route
function has_route($name)
{
    return Route::has($name);
}

// define route
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

// load routes
function load_routes($name, $middleware = 'web')
{
    $path = str($name)->replace('.', '/')->finish('.php')->toString();

    if ($middleware) {
        Route::middleware($middleware)->group(__DIR__.'/../../routes/'.$path);
    }
    else {
        Route::group(__DIR__.'/../../routes/'.$path);
    }
}