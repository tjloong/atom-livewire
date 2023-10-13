<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class ResetPassword extends Component
{
    use WithForm;

    public $token;
    public $inputs;

    // validations
    protected function validation(): array
    {
        return [
            'token' => [
                'required' => 'Token is missing.',
            ],
            'inputs.email' => [
                'required' => 'Email is required.',
                'email' => 'Invalid email.',
            ],
            'inputs.password' => [
                'required' => 'Password is required.',
                'min:8' => 'Password must be at least 8 characters.',
                'confirmed' => 'Password confirmation mismatched.',
            ],
            'inputs.password_confirmation' => [
                'required' => 'Confirm password is required.',
            ],
        ];
    }

    // mount
    public function mount()
    {
        $this->fill([
            'token' => request()->query('token'),
            'inputs.email' => request()->query('email'),
            'inputs.password' => null,
            'inputs.password_confirmation' => null,
        ]);
    }

    // submit
    public function submit()
    {
        $this->validateForm();

        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => data_get($this->inputs, 'email'),
                'password' => data_get($this->inputs, 'password'),
                'password_confirmation' => data_get($this->inputs, 'password_confirmation'),
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