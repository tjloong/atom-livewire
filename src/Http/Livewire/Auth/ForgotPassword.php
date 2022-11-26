<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    /**
     * Submit
     */
    public function submit()
    {
        if ($user = User::where('email', $this->email)->first()) {
            if ($status = $user->sendPasswordResetLink()) return redirect()->route('login')->with(['flash' => __($status)]);
            else $this->addError('email', __('Unable to reset password'));
        }
        else $this->addError('email', __('Email not found'));
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('auth.forgot-password');
    }
}