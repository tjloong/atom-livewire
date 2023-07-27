<?php

namespace Jiannius\Atom\Enums\Banner;

enum Status: string
{
    case ACTIVE = 'active';
    case UPCOMING = 'upcoming';
    case ENDED = 'ended';
    case INACTIVE = 'inactive';

    public function color()
    {
        return match($this) {
            static::ACTIVE => 'green',
            static::UPCOMING => 'yellow',
            static::ENDED => 'gray',
            static::INACTIVE => 'gray',
        };
    }

    public function option()
    {
        return match($this) {
            static::ACTIVE => ['value' => $this->value, 'label' => str()->headline($this->value)],
            static::UPCOMING => ['value' => $this->value, 'label' => str()->headline($this->value)],
            static::ENDED => ['value' => $this->value, 'label' => str()->headline($this->value)],
            static::INACTIVE => ['value' => $this->value, 'label' => str()->headline($this->value)],
        };
    }
}