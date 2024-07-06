<?php

namespace Jiannius\Atom\Enums\Sendmail;

use Jiannius\Atom\Traits\Enum;

enum Status : string
{
    use Enum;

    case QUEUE = 'queue';
    case SENDING = 'sending';
    case SENT = 'sent';
    case FAILED = 'failed';

    public function color()
    {
        return match($this) {
            static::QUEUE => 'purple',
            static::SENDING => 'yellow',
            static::SENT => 'green',
            static::FAILED => 'red',
        };
    }
}