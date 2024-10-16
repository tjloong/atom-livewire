<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum ShareStatus: string
{
    use Enum;

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
}