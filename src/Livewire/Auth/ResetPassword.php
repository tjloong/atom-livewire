<?php

namespace Jiannius\Atom\Livewire\Auth;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class ResetPassword extends Component
{
    use AtomComponent;

    public $token;
    public $inputs;

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

    public function mount()
    {
        $this->fill([
            'token' => request()->query('token'),
            'inputs.email' => request()->query('email'),
            'inputs.password' => null,
            'inputs.password_confirmation' => null,
        ]);
    }

    public function submit()
    {
        $this->validate();

        if (Atom::action('reset-password', [
            'token' => $this->token,
            ...$this->inputs,
        ])) {
            return to_route('login')->with('message', t('passwords.reset'));
        }

        $this->addError('reset', t('failed'));
        $this->refresh();
    }
}
