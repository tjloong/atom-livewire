<?php

namespace Jiannius\Atom\Traits;

trait Enum
{
    // option
    public function option() : array
    {
        return ['value' => $this->value, 'label' => $this->label()];
    }

    // label
    public function label() : string
    {
        return str()->headline($this->value);
    }

    // badge
    public function badge() : array
    {
        return [$this->color() => $this->value];
    }
}