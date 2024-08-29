<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum AnnouncementStatus : string
{
    use Enum;

    case EXPIRED = 'expired';
    case UPCOMING = 'upcoming';
    case PUBLISHED = 'published';

    public function color()
    {
        return match($this) {
            static::EXPIRED => 'gray',
            static::UPCOMING => 'blue',
            static::PUBLISHED => 'green',
        };
    }
}