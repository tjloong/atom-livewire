<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Jiannius\Atom\Component;

class ForgotPassword extends Component
{
    public $email;

    // submit
    public function submit()
    {
        if ($user = model('user')
            ->where('email', $this->email)
            ->whereNull('blocked_at')
            ->first()
        ) {
            if ($status = $user->sendPasswordResetLink()) return to_route('login')->with(['flash' => __($status)]);
            else $this->addError('email', __('Unable to reset password'));
        }
        else $this->addError('email', __('Email not found'));
    }
}