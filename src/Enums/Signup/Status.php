<?php

namespace Jiannius\Atom\Enums\Signup;

enum Status: string
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
        return match($this) {
            static::TRASHED => 'Trashed',
            static::BLOCKED => 'Blocked',
            static::ONBOARDED => 'Onboarded',
            static::NEW => 'New',
        };
    }

    public function option()
    {
        return match($this) {
            static::TRASHED => ['value' => $this->value, 'label' => $this->label()],
            static::BLOCKED => ['value' => $this->value, 'label' => $this->label()],
            static::ONBOARDED => ['value' => $this->value, 'label' => $this->label()],
            static::NEW => ['value' => $this->value, 'label' => $this->label()],
        };
    }
}