<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Auth\Events\Registered;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Laravel\Socialite\Facades\Socialite;

class Register extends Component
{
    use WithForm;

    public $utm;
    public $user;
    public $signup;
    public $refcode;
    public $redirect;
    public $verification;
    public $hasValidSignature;
    
    public $inputs = [
        'name' => null,
        'email' => null,
        'password' => null,
        'agree_tnc' => false,
        'agree_promo' => true,
        'verification' => null,
    ];

    private $socialiteUser;

    // validation
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
                    if ($this->user) $fail('Email is taken');
                },
            ],
            'inputs.password' => [
                'required' => 'Login password is required.',
                'min:8' => 'Login password must be at least 8 characters.',
            ],
            'inputs.agree_tnc' => ['accepted' => 'Please accept the terms and conditions to proceed.'],
            'inputs.agree_promo' => ['nullable'],
        ];
    }

    // mount
    public function mount()
    {
        $this->redirect = request()->query('redirect');

        if (
            ($token = request()->query('token'))
            && ($provider = request()->query('provider'))
            && ($user = rescue(fn() => optional(Socialite::driver($provider))->userFromToken($token)))
        ) {
            $this->socialiteUser = $user;

            $this->fill([
                'inputs' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => str()->snake($user->getName()).'_oauth',
                    'email_verified_at' => now(),
                    'agree_tnc' => true,
                    'agree_promo' => true,
                    'data' => ['oauth' => [
                        'provider' => $provider,
                        'id' => $user->getId(),
                        'nickname' => $user->getNickname(),
                        'avatar' => $user->getAvatar(),
                        'token' => $user->token,
                        'token_secret' => $user->tokenSecret,
                        'refresh_token' => $user->refreshToken,
                        'expires_in' => $user->expiresIn,
                    ]],
                ],
            ]);

            return $this->submit();
        }
        else {
            $this->fill([
                'refcode' => request()->query('refcode') ?? request()->query('ref'),
                'utm' => [
                    'campaign' => request()->query('utm_campaign'),
                    'medium' => request()->query('utm_medium'),
                    'source' => request()->query('utm_source'),
                ],
                'hasValidSignature' => request()->hasValidSignature(),
                'inputs' => [
                    ...$this->inputs,
                    ...(
                        request()->query('email')
                        ? ['email' => request()->query('email')]
                        : request()->query('fill', [])
                    ),
                ],
            ]);
        }
    }

    // get social logins property
    public function getSocialLoginsProperty() : mixed
    {
        return model('setting')->getSocialLogins();
    }

    // get user
    public function getUser() : void
    {
        $this->user = model('user')
            ->where('email', get($this->inputs, 'email'))
            ->first();
    }

    // submit
    public function submit() : mixed
    {
        $this->getUser();

        if ($this->socialiteUser) {
            if ($this->user) return to_route('login', request()->query());
            else {
                $this->createUser();
                $this->createSignup();

                return $this->registered();
            }
        }
        else {
            $this->validateForm();

            if ($this->verify()) {
                $this->createUser();
                $this->createSignup();

                return $this->registered();
            }

            return null;
        }
    }

    // verified
    public function verify() : bool
    {
        if (!config('atom.auth.verify')) return true;
        if ($this->hasValidSignature) return true;

        if ($code = get($this->inputs, 'verification')) {
            $verified = model('verification')
                ->where('email', get($this->inputs, 'email'))
                ->where('code', $code)
                ->where(fn($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>', now()))
                ->count() > 0;

            if ($verified) {
                $this->clearVerificationCode();
                return true;
            }
            
            $this->popup('auth.alert.verification', 'alert', 'error');
        }
        else {
            $this->sendVerificationCode();
        }

        return false;
    }

    // send verification code
    public function sendVerificationCode() : void
    {
        if (config('atom.auth.verify')) {
            $this->clearVerificationCode();

            $this->verification = model('verification')->create([
                'email' => get($this->inputs, 'email'),
                'expired_at' => now()->addDay(),
            ]);
        }
        else if (config('atom.auth.otp')) {
            //
        }
    }

    // clear verification code
    public function clearVerificationCode() : void
    {
        model('verification')
            ->where('email', get($this->inputs, 'email'))
            ->delete();

        $this->fill(['inputs.verification' => null]);
    }

    // create user
    public function createUser() : void
    {
        $this->user = model('user')->forceFill([
            'name' => get($this->inputs, 'name'),
            'email' => get($this->inputs, 'email'),
            'password' => bcrypt(get($this->inputs, 'password')),
            'tier' => 'signup',
            'data' => get($this->inputs, 'data'),
            'email_verified_at' => now(),
            'login_at' => now(),
        ]);

        $this->user->save();
    }

    // create signup
    public function createSignup() : void
    {
        $this->signup = $this->user->signup()->create([
            'refcode' => $this->refcode,
            'utm' => $this->utm,
            'geo' => geoip()->getLocation()->toArray(),
            'agree_tnc' => get($this->inputs, 'agree_tnc'),
            'agree_promo' => get($this->inputs, 'agree_promo'),
        ]);
    }

    // post registration
    public function registered() : mixed
    {
        auth()->login($this->user);

        event(new Registered($this->user->fresh()));

        return redirect($this->redirect ?? $this->user->home());
    }
}