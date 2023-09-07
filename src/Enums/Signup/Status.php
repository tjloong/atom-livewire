<?php

namespace Jiannius\Atom\Enums\Signup;

enum Status : string
{
    case TRASHED = 'trashed';
    case BLOCKED = 'blocked';
    case ONBOARDED = 'onboarded';
    case NEW = 'new';

    public function color()
    {
        return match($this) {
            static::TRASHED => 'black',
            static::BLOCKED => 'black',
            static::ONBOARDED => 'green',
            static::NEW => 'yellow',
        };
    }

    public function label()
    {
        return str()->headline($this->value);
    }

    public function option()
    {
        return ['value' => $this->value, 'label' => $this->label()];
    }

    public function badge()
    {
        return [$this->color() => $this->value];
    }
}