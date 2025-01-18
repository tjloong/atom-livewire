<?php

namespace Jiannius\Atom\Actions;

use Illuminate\Support\Facades\Password;

class SendPasswordResetLink
{
    public function __construct(public $params)
    {
        //
    }

    public function run()
    {
        $user = $this->getUser();

        if (!$user) return false;

        $status = Password::sendResetLink(['email' => $user->email]);
    
        if ($status === Password::RESET_LINK_SENT) return $status;

        return false;
    }

    public function getUser()
    {
        return model('user')
            ->loginable()
            ->where('email', get($this->params, 'email'))
            ->first();
    }
}
