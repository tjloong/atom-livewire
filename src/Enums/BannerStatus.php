<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum BannerStatus : string
{
    use Enum;

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
}