<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Services\Alert;
use Jiannius\Atom\Services\Confirm;
use Jiannius\Atom\Services\Modal;
use Jiannius\Atom\Services\Toast;

class Atom
{
    // alert
    public static function alert($message, $type = null)
    {
        return app(Alert::class)->make($message, $type);
    }

    // confirm
    public static function confirm($message, $type = null)
    {
        return app(Confirm::class)->make($message, $type);
    }

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
