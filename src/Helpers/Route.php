<?php

use Illuminate\Support\Facades\Route;

// get current route
function current_route()
{
    $args = func_get_args();
    $routes = count($args) > 1 ? $args : head($args);
    $currentRoute = request()->route()->getName();

    return $routes
        ? collect($routes)->contains(fn($val) => str($currentRoute)->is($val))
        : $currentRoute;
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