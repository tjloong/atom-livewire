<?php

namespace Jiannius\Atom\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

trait Enum
{
    /**
     * Get enum from name or value
     */
    public static function get($name)
    {
        if (!is_string($name)) return $name;

        return static::tryFrom($name)
            ?? static::all()->first(fn ($case) => $case->is(strtoupper($name)));
    }

    /**
     * Get all enum cases
     */
    public static function all($filtered = true) : Collection
    {
        $cases = collect(static::cases());

        return $filtered
            ? $cases->filter(fn($case) => $case->isNot('TRASHED'))->values()
            : $cases;
    }

    /**
     * Get enum as option array with value and label
     */
    public function option() : array
    {
        return ['value' => $this->value, 'label' => $this->label()];
    }

    /**
     * Get formatted label from enum value
     */
    public function label() : string
    {
        return str()->headline($this->value);
    }

    /**
     * Get badge array with color and label
     */
    public function badge() : array
    {
        return [
            'color' => $this->color(),
            'label' => $this->label(),
        ];
    }

    /**
     * Check if enum matches given value(s)
     */
    public function is() : bool
    {
        $val = func_num_args() > 1 ? func_get_args() : (array) func_get_arg(0);

        return in_array($this->value, (array) $val) || in_array($this->name, (array) $val);
    }

    /**
     * Check if enum does not match given value(s)
     */
    public function isNot(...$val) : bool
    {
        return !$this->is(...$val);
    }

    /**
     * Get enum value as Stringable
     */
    public function str() : Stringable
    {
        return new Stringable($this->value);
    }

    /**
     * Convert enum name to snake case
     */
    public function snake() : string
    {
        return (string) str($this->name)->lower()->snake();
    }

    /**
     * Convert enum name to slug
     */
    public function slug() : string
    {
        return (string) str($this->name)->lower()->slug();
    }
}