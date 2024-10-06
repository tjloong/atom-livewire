<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Services\Alert;
use Jiannius\Atom\Services\Confirm;
use Jiannius\Atom\Services\Icon;
use Jiannius\Atom\Services\Logo;
use Jiannius\Atom\Services\Html;
use Jiannius\Atom\Services\Modal;
use Jiannius\Atom\Services\Sheet;
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

    // sheet
    public static function sheet($name = null)
    {
        return app(Sheet::class)->name($name);
    }

    // icon
    public static function icon($name)
    {
        return Icon::get($name);
    }

    // logo
    public static function logo($name)
    {
        return Logo::get($name);
    }

    // html
    public static function html()
    {
        return app(Html::class);
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

    // check has livewire component
    public function hasLivewireComponent($path)
    {
        $class = str($path)->namespace();

        return !empty(
            collect([
                "App\Http\Livewire\\$class",
                "App\Http\Livewire\\$class\\Index",
                "Jiannius\Atom\Http\Livewire\\$class",
                "Jiannius\Atom\Http\Livewire\\$class\\Index",
            ])->first(fn($s) => class_exists($s))
        );
    }

    // check variable is enum
    public function isEnum($value)
    {
        return $value instanceof \UnitEnum
            || $value instanceof \BackedEnum;
    }
}
