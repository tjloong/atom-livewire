<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithLoginMethods
{
    public function isLoginMethod($methods, $strict = false): bool
    {
        if ($strict) {
            return !collect((array) $methods)
                ->contains(fn($method) => !in_array($method, config('atom.auth.login')));
        }
        else {
            return collect((array) $methods)
                ->contains(fn($method) => in_array($method, config('atom.auth.login')));
        }
    }
}