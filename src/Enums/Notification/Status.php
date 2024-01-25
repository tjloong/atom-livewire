<?php

namespace Jiannius\Atom\Enums\Notification;

use Jiannius\Atom\Traits\Enum;

enum Status : string
{
    use Enum;

    case PENDING = 'pending';
    case SENT = 'sent';
    case FAILED = 'failed';

    public function color()
    {
        return match($this) {
            static::PENDING => 'blue',
            static::SENT => 'green',
            static::FAILED => 'red',
        };
    }
}