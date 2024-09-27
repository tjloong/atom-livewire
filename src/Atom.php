<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Services\Modal;
use Jiannius\Atom\Services\Toast;

class Atom
{
    // toast
    public static function toast($message, $type = null)
    {
        return app(Toast::class)->make($message, $type);
    }

    // modal
    public static function modal($name = null)
    {
        return app(Modal::class)->name($name);
    }

    // call
    public function __call($name, $args)
    {
        return $this->resolve($name, $args);
    }

    // call static
    public static function __callStatic($name, $args)
    {
        return (new self())->resolve($name, $args);
    }
    
    // resolve
    public function resolve($name, $args)
    {
        $name = str()->studly($name);
        $class = "\Jiannius\Atom\Services\\$name";

        return new $class(...$args);
    }
}
