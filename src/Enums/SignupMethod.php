<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum SignupMethod : string
{
    use Enum;

    case WEB = 'web';
    case OAUTH = 'oauth';
}