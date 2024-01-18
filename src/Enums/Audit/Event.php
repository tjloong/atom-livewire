<?php

namespace Jiannius\Atom\Enums\Audit;

use Jiannius\Atom\Traits\Enum;

enum Event : string
{
    use Enum;

    case CREATED = 'created';
    case UPDATED = 'updated';
    case TRASHED = 'trashed';
    case DELETED = 'deleted';

    public function color()
    {
        return match($this) {
            static::CREATED => 'green',
            static::UPDATED => 'blue',
            static::TRASHED => 'red',
            static::DELETED => 'red',
        };
    }
}