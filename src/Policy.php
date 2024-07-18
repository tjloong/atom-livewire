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
        $role = (array) explode_if(['|', ',', '/'], $role);

        return $user->isRole(...$role);
    }

    // check permission
    public function permission($user, $permission) : bool
    {
        return $user->isPermitted(explode_if(['|', ',', '/'], $permission));
    }
}