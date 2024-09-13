<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum BannerPlacement : string
{
    use Enum;

    case HOME = 'home';
}