<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class ResetPassword extends Component
{
    public $email;
    public $token;
    public $password;
    public $passwordConfirm;

    /**
     * Mount event
     */
    public function mount()
    {
        $this->email = request()->query('email');
        $this->token = request()->query('token');
    }

    /**
     * Save password
     */
    public function save()
    {
        $this->validateinputs();

        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->passwordConfirm,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    'email_verified_at' => now(),
                    'activated_at' => $user->activated_at ?? now(),
                ])->setRememberToken(Str::random(60));
    
                $user->saveQuietly();
            }
        );

        if ($status === Password::PASSWORD_RESET) return redirect()->route('login')->with('flash', __($status));
        else {
            $this->resetValidation();
            $this->addError('email', __($status));
        }
    }

    /**
     * Validate inputs
     */
    private function validateinputs()
    {
        validator(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->passwordConfirm,
            ],
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',    
            ],
            [
                'token.required' => 'Token is missing.',
                'email.required' => 'Email is required.',
                'email.email' => 'Invalid email.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',        
                'password.confirmed' => 'Please confirm your password.',    
            ]
        )->validate();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('auth.reset-password');
    }
}