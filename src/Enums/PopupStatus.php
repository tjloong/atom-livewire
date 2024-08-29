<?php

namespace Jiannius\Atom\Enumm;

use Jiannius\Atom\Traits\Enum;

enum PopupStatus : string
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