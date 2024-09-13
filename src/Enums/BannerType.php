<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum BannerType : string
{
    use Enum;

    case MAINBANNER = 'mainbanner';
}