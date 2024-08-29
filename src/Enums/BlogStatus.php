<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum BlogStatus : string
{
    use Enum;

    case DRAFT = 'draft';
    case UPCOMING = 'upcoming';
    case PUBLISHED = 'published';

    public function color()
    {
        return match($this) {
            static::DRAFT => 'gray',
            static::UPCOMING => 'yellow',
            static::PUBLISHED => 'green',
        };
    }
}