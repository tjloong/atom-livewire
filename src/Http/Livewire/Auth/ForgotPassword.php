<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class ForgotPassword extends Component
{
    use WithForm;

    public $email;

    // validation
    protected function validation() : array
    {
        return [
            'email' => [
                'required' => 'Email is required.',
                'email' => 'Invalid email.',
                function ($attr, $value, $fail) {
                    if (!$this->getUser()) $fail(tr('auth.alert.password-user'));
                },
            ],
        ];
    }

    // get user
    public function getUser() : mixed
    {
        return model('user')
            ->where('email', $this->email)
            ->whereNull('blocked_at')
            ->first();
    }

    // submit
    public function submit() : mixed
    {
        $this->validateForm();

        if ($user = $this->getUser()) {
            if ($user->sendPasswordResetLink()) {
                session()->flash('message', tr('auth.alert.password-sent'));
                return to_route('auth.login');
            }
            else return $this->popup('auth.alert.password-reset-failed', 'alert', 'error');
        }
    }
}