<?php

namespace Jiannius\Atom\Services;

class Route
{
    // __call
    public function __call($name, $arguments)
    {
        if (in_array($name, ['get', 'post', 'put', 'patch', 'delete', 'options'])) {
            $path = $arguments[0];
            $callback = $this->getCallback($arguments[1]);
            $arguments = [$path, $callback];
        }
        else if ($name === 'match') {
            $methods = $arguments[0];
            $path = $arguments[1];
            $callback = $this->getCallback($arguments[2]);
            $arguments = [$methods, $path, $callback];
        }
        
        return \Illuminate\Support\Facades\Route::$name(...$arguments);
    }

    // get callback
    public function getCallback($callback): mixed
    {
        if (is_string($callback)) {
            if (str($callback)->is('*Controller*')) {
                if (str($callback)->is('*@*')) [$postfix, $method] = explode('@', $callback);
                else $postfix = $callback;

                $class = collect([
                    'App\Http\Controllers\\'.$postfix,
                    'Jiannius\Atom\Http\Controllers\\'.$postfix,
                ])->first(fn($ns) => class_exists($ns));

                return isset($method) ? [$class, $method] : $class;
            }
            else {
                return collect([
                    'App\Http\Livewire\\'.$callback,
                    'App\Http\Livewire\\'.$callback.'\Index',
                    'Jiannius\Atom\Http\Livewire\\'.$callback,
                    'Jiannius\Atom\Http\Livewire\\'.$callback.'\Index',
                ])->first(fn($ns) => class_exists($ns));
            }
        }
        else return $callback;
    }
}