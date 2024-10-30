<?php

namespace Jiannius\Atom\Services;

class Enum
{
    public $ns;

    // constructor
    public function __construct($name)
    {
        if (str($name)->is('App\\Enums\\*') || str($name)->is('Jiannius\\Atom\\Enums\\*')) {
            $this->ns = $name;
        }
        else {
            $name = collect(explode('.', $name))
                ->map(fn($val) => str($val)->singular()->studly()->toString())
                ->join('\\');

            $this->ns = collect([
                'App\\Enums\\'.$name,
                'Jiannius\\Atom\\Enums\\'.$name,
            ])->first(fn($val) => file_exists(atom_ns_path($val)));
        }
    }

    // get
    public function get($name) : mixed
    {
        $enum = $this->ns::tryFrom($name);

        if (!$enum) $enum = defined("{$this->ns}::$name") ? constant("{$this->ns}::$name") : null;

        if (!$enum) {
            $name = (string) str($name)->headline()->snake()->upper();
            $enum = defined("{$this->ns}::$name") ? constant("{$this->ns}::$name") : null;
        }

        return $enum;
    }

    // all
    public function all($filtered = true) : mixed
    {
        $cases = collect($this->ns::cases());

        return $filtered
            ? $cases->filter(fn($case) => !in_array($case->name, ['TRASHED']))->values()
            : $cases;
    }

    // default
    public function default() : mixed
    {
        return $this->get('__DEFAULT');
    }
}