<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Services\Alert;
use Jiannius\Atom\Services\Confirm;
use Jiannius\Atom\Services\Country;
use Jiannius\Atom\Services\Icon;
use Jiannius\Atom\Services\Logo;
use Jiannius\Atom\Services\Html;
use Jiannius\Atom\Services\Modal;
use Jiannius\Atom\Services\Sheet;
use Jiannius\Atom\Services\Toast;

class Atom
{
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

    // options
    public static function options($name, $filters = [])
    {
        $class = collect([
            "App\Services\Options",
            "Jiannius\Atom\Services\Options",
        ])->first(fn($s) => class_exists($s));

        return app($class)->filter($filters)->$name();
    }

    // country
    public static function country($name = null, $field = null)
    {
        $country = app(Country::class);
        return $name ? $country->get($name, $field) : $country->all();
    }

    public static function hasLivewireComponent($path)
    {
        $namespace = str($path)->namespace()->replace('\\', '/');

        $paths = [
            app_path("Http/Livewire/$namespace.php"),
            atom_path("src/Http/Livewire/$namespace.php"),
        ];

        if (!$namespace->is('*/Index')) {
            $paths = [
                ...$paths,
                app_path("Http/Livewire/$namespace/Index.php"),
                atom_path("src/Http/Livewire/$namespace/Index.php"),
            ];
        }

        return collect($paths)
            ->filter(fn($val) => file_exists($val))
            ->isNotEmpty();
    }
}
