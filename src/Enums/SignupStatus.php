<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum SignupStatus : string
{
    use Enum;

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
}