<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithLoginMethods
{
    public function isLoginMethod($methods, $operator = 'or'): bool
    {
        if ($operator === 'and') {
            return !collect((array) $methods)
                ->contains(fn($method) => !in_array($method, config('atom.auth.login')));
        }
        elseif ($operator === 'or') {
            return collect((array) $methods)
                ->contains(fn($method) => in_array($method, config('atom.auth.login')));
        }
    }
}