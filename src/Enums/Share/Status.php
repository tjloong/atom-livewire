<?php

namespace Jiannius\Atom\Enums\Share;

enum Status: string
{
    case ACTIVE = 'active';
    case DISABLED = 'disabled';
    case EXPIRED = 'expired';

    public function color()
    {
        return match($this) {
            static::ACTIVE => 'green',
            static::DISABLED => 'gray',
            static::EXPIRED => 'gray',
        };
    }

    public function option()
    {
        return ['value' => $this->value, 'label' => $this->label()];
    }

    public function label()
    {
        return str()->headline($this->value);
    }
}