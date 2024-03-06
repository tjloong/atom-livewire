<?php

namespace Jiannius\Atom\Enums\Banner;

use Jiannius\Atom\Traits\Enum;

enum Placement : string
{
    use Enum;

    case HOME = 'home';
}