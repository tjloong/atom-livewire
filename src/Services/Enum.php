<?php

namespace Jiannius\Atom\Services;

class Enum
{
    public $ns;

    // constructor
    public function __construct($name)
    {
        $name = collect(explode('.', $name))
            ->map(fn($val) => str($val)->singular()->studly()->toString())
            ->join('\\');

        $this->ns = collect([
            'App\\Enums\\'.$name,
            'Jiannius\\Atom\\Enums\\'.$name,
        ])->first(fn($val) => file_exists(atom_ns_path($val)));
    }

    // get
    public function get($getter) : mixed
    {
        return $this->ns::tryFrom($getter) ?? constant("{$this->ns}::{$getter}") ?? null;
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
        return defined("{$this->ns}::__DEFAULT") ? $this->ns::__DEFAULT : null;
    }
}