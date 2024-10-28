<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum ShareableStatus: string
{
    use Enum;

    case ACTIVE = 'active';
    case EXPIRED = 'expired';

    public function color()
    {
        return match($this) {
            static::ACTIVE => 'green',
            static::EXPIRED => 'gray',
        };
    }
}