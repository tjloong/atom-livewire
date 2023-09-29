<?php

namespace Jiannius\Atom\Enums\User;

enum Status: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';
    case TRASHED = 'trashed';

    public function label()
    {
        return str()->title($this->value);
    }

    public function option()
    {
        return ['value' => $this->value, 'label' => $this->label()];
    }

    public function color()
    {
        return match($this) {
            static::ACTIVE => 'green',
            static::INACTIVE => 'gray',
            static::BLOCKED => 'gray',
            static::TRASHED => 'black',
        };
    }

    public function badge()
    {
        return [$this->color() => $this->value];
    }
}