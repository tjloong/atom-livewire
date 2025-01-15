<?php

namespace Jiannius\Atom\Livewire\Auth;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class ForgotPassword extends Component
{
    use AtomComponent;

    public $email;

    protected function validation() : array
    {
        return [
            'email' => [
                'required' => 'Email is required.',
                'email' => 'Invalid email.',
                function ($attr, $value, $fail) {
                    if (!$this->getUser()) $fail(t('we-cant-find-user-with-that-email-address'));
                },
            ],
        ];
    }

    public function getUser() : mixed
    {
        return model('user')
            ->where('email', $this->email)
            ->whereNull('blocked_at')
            ->first();
    }

    public function submit() : mixed
    {
        $this->validate();

        if ($user = $this->getUser()) {
            if ($user->sendPasswordResetLink()) {
                session()->flash('message', t('we-have-emailed-your-password-reset-link'));
                return to_route('login');
            }
            else Atom::alert('unable-to-reset-password', 'error');
        }
    }
}