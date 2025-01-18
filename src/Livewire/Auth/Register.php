<?php

namespace Jiannius\Atom\Livewire\Auth;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Models\Passcode;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Register extends Component
{
    use AtomComponent;

    public $utm;
    public $refcode;
    public $passcode;
    public $redirect;
    public $signature = false;

    public $inputs = [
        'name' => null,
        'email' => null,
        'password' => null,
        'agree_tnc' => false,
        'agree_promo' => true,
    ];

    protected function validation() : array
    {
        return [
            'inputs.name' => [
                'required' => 'Name is required.',
                'string' => 'Name must be a string.',
                'max:255' => 'Name is too long (Max 255 characters).',
            ],
            'inputs.email' => [
                'required' => 'Login email is required.',
                'email' => 'Invalid email.',
                function ($attr, $value, $fail) {
                    if (model('user')->where('email', $value)->count()) {
                        $fail('Email is taken');
                    }
                },
            ],
            'inputs.password' => [
                'required' => 'Login password is required.',
                'min:8' => 'Login password must be at least 8 characters.',
            ],
            'inputs.agree_tnc' => ['accepted' => 'Please accept the terms and conditions to proceed.'],
            'inputs.agree_promo' => ['nullable'],

            'passcode' => [
                'nullable',
                function ($attr, $value, $fail) {
                    if (!Passcode::verify(
                        email: get($this->inputs, 'email'),
                        code: $value,
                    )) {
                        $fail(t('incorrect-verification-code'));
                    }
                }
            ]
        ];
    }

    public function mount()
    {
        $this->refcode = request()->query('refcode') ?? request()->query('ref');
        $this->signature = request()->hasValidSignature();
        $this->utm = [
            'campaign' => request()->query('utm_campaign'),
            'medium' => request()->query('utm_medium'),
            'source' => request()->query('utm_source'),
        ];

        if ($user = Atom::action('get-socialite-user', [
            'token' => request()->query('token'),
            'provider' => request()->query('provider'),
        ])) {
            if ($user->exists) return to_route('login', request()->query());

            $this->inputs = $user->toArray();

            return $this->register();
        }

        $this->inputs = [
            ...$this->inputs,
            ...(
                request()->query('email')
                ? ['email' => request()->query('email')]
                : request()->query('fill', [])
            ),
        ];
    }

    public function resend()
    {
        Passcode::resend(
            email: get($this->inputs, 'email')
        );
    }

    public function submit()
    {
        $this->validate();

        if ($this->signature) return $this->register();

        if (config('atom.auth.verify_method')) {
            Passcode::create([
                'email' => get($this->inputs, 'email'),
                'phone' => get($this->inputs, 'phone'),
            ]);

            return Atom::modal('passcode')->show();
        }

        return $this->register();
    }

    public function verify()
    {
        $this->validateOnly('passcode');
        $this->register();
    }

    public function register()
    {
        $response = Atom::action('register', [
            'data' => $this->inputs,
            'redirect' => $this->redirect,
            'utm' => $this->utm,
            'refcode' => $this->refcode,
        ]);

        if ($err = get($response, 'error')) {
            return $this->addError('register', $err);
        }

        return $response;
    }
}
