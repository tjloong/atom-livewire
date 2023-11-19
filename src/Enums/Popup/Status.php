<?php

namespace Jiannius\Atom\Enums\Popup;

use Jiannius\Atom\Traits\Enum;

enum Status : string
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