<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum PushNotificationStatus : string
{
    use Enum;

    case ARCHIVED = 'archived';
    case READ = 'read';
    case UNREAD = 'unread';
}