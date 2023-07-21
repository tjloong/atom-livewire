<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class ResetPassword extends Component
{
    use WithForm;

    public $email;
    public $token;
    public $password;
    public $passwordConfirm;

    // validations
    protected function validations(): array
    {
        return [
            'token' => [
                'required' => 'Token is missing.',
            ],
            'email' => [
                'required' => 'Email is required.',
                'email' => 'Invalid email.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min:8' => 'Password must be at least 8 characters.',
                'confirmed' => 'Please confirm your password.',
            ],
            'password_confirmation' => [
                'required' => 'Confirm password is required.',
            ],
        ];
    }

    // mount
    public function mount()
    {
        parent::mount();

        $this->email = request()->query('email');
        $this->token = request()->query('token');
    }

    // submit
    public function submit()
    {
        $this->validateForm();

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
                ])->setRememberToken(str()->random(60));
    
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return to_route('login')->with('flash', __($status));
        }
        else {
            $this->resetValidation();
            $this->addError('email', __($status));
        }
    }
}