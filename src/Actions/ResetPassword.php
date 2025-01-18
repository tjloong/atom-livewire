<?php

namespace Jiannius\Atom\Actions;

use Illuminate\Support\Facades\Password;

class ResetPassword
{
    public function __construct(public $params)
    {
        //
    }

    public function run()
    {
        $status = Password::reset($this->params, function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password),
                'email_verified_at' => now(),
            ])->setRememberToken(str()->random(60));

            $user->save();
        });

        return $status === Password::PASSWORD_RESET;
    }
}