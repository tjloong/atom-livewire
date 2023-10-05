<?php

namespace Jiannius\Atom\Enums\Enquiry;

use Jiannius\Atom\Traits\Enum;

enum Status : string
{
    use Enum;

    case PENDING = 'pending';
    case CLOSED = 'closed';

    public function color()
    {
        return match($this) {
            static::PENDING => 'blue',
            static::CLOSED => 'gray',
        };
    }
}