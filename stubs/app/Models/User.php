<?php

namespace App\Models;

use Jiannius\Atom\Traits\HasRole;
use Jiannius\Atom\Traits\HasTeam;
use Jiannius\Atom\Models\User as AtomUser;

class User extends AtomUser
{
    use HasRole;
    use HasTeam;

    public $mustVerifyEmail = true;
}