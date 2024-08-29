<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum UserStatus: string
{
    use Enum;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';
    case TRASHED = 'trashed';

    public function color()
    {
        return match($this) {
            static::ACTIVE => 'green',
            static::INACTIVE => 'gray',
            static::BLOCKED => 'gray',
            static::TRASHED => 'black',
        };
    }
}