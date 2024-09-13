<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum EnquiryStatus : string
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