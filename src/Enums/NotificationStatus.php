<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum NotificationStatus : string
{
    use Enum;

    case ARCHIVED = 'archived';
    case READ = 'read';
    case UNREAD = 'unread';
}