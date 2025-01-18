<?php

namespace Jiannius\Atom\Livewire\Auth;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Login extends Component
{
    use AtomComponent;

    public $redirect;

    public $inputs = [
        'email' => null,
        'password' => null,
        'remember' => false,
    ];

    protected function validation() : array
    {
        return [
            'inputs.email' => ['required' => 'Email is required.'],
            'inputs.password' => ['required' => 'Password is required.'],
        ];
    }

    public function mount()
    {
        $this->fill([
            'redirect' => request()->query('redirect'),
            'inputs.email' => request()->query('email')
                ?? request()->query('username')
                ?? request()->query('fill.email')
                ?? request()->query('fill.username'),
        ]);

        if ($user = Atom::action('get-socialite-user', [
            'token' => request()->query('token'),
            'provider' => request()->query('provider'),
        ])) {
            if ($user->exists) return $this->login($user);
            else return to_route('register', request()->query());
        }
    }

    public function submit()
    {
        $this->validate();
        return $this->login();
    }

    public function login($user = null)
    {
        $response = Atom::action('login', [
            'data' => $this->inputs,
            'user' => $user,
            'redirect' => $this->redirect,
        ]);

        if ($err = get($response, 'error')) return $this->addError('login', $err);

        return $response;
    }
}
