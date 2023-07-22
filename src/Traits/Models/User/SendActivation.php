<?php

namespace Jiannius\Atom\Traits\Models\User;

use Jiannius\Atom\Notifications\Auth\ActivateNotification;

trait SendActivation
{
    // boot
    protected static function bootSendActivation(): void
    {
        static::created(function($user) {
            if (!$user->password) $user->notify(new ActivateNotification());
        });
    }
}