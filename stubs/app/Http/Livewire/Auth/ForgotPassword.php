<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public $email;

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('layouts.auth');
    }

    /**
     * Send password reset request
     */
    public function send()
    {
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) return redirect()->route('login')->with(['flash' => __($status)]);
        else $this->addError('email', __($status));
    }
}