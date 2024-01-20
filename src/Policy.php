<?php

namespace Jiannius\Atom;

class Policy
{
    // check tier
    public function tier($user, $tier) : bool
    {
        return $user->isTier(explode_if(['|', ',', '/'], $tier));
    }

    // check role
    public function role($user, $role) : bool
    {
        return $user->isRole(explode_if(['|', ',', '/'], $role));
    }

    // check permission
    public function permission($user, $permission) : bool
    {
        return $user->isPermitted(explode_if(['|', ',', '/'], $permission));
    }
}