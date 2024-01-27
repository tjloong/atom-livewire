<?php

namespace Jiannius\Atom\Enums\Notilog;

use Jiannius\Atom\Traits\Enum;

enum Status : string
{
    use Enum;

    case SENT = 'sent';
    case SENDING = 'sending';
    case FAILED = 'failed';

    public function color()
    {
        return match($this) {
            static::SENT => 'green',
            static::SENDING => 'blue',
            static::FAILED => 'red',
        };
    }
}