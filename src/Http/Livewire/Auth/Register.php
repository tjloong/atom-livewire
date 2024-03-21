<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Laravel\Socialite\Facades\Socialite;

class Register extends Component
{
    use WithForm;

    public $ref;
    public $utm;
    public $plan;
    public $token;
    public $provider;
    public $verification;
    public $hasValidSignature;

    public $inputs = [
        'name' => null,
        'email' => null,
        'password' => null,
        'agree_tnc' => false,
        'agree_promo' => true,
    ];

    protected $queryString = ['ref', 'utm', 'token', 'provider', 'plan'];
    
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
                'unique:users,email' => 'Email is taken.',
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
        if (!$this->ref && !$this->utm && !$this->token && !$this->provider) {
            return redirect('/');
        }
        else if (
            $this->token && $this->provider
            && ($socialite = rescue(fn() => Socialite::driver($this->provider)->userFromToken($this->token)))
        ) {
            if (model('user')->firstWhere('email', $socialite->getEmail())) {
                return to_route('login', array_merge([
                    'token' => $this->token,
                    'provider' => $this->provider,
                ], request()->query()));
            }
            else {
                return $this->register([
                    'name' => $socialite->getName(),
                    'email' => $socialite->getEmail(),
                    'password' => str()->snake($socialite->getName()).'_oauth',
                    'email_verified_at' => now(),
                    'agree_tnc' => true,
                    'agree_promo' => true,
                    'data' => ['oauth' => [
                        'provider' => $this->provider,
                        'id' => $socialite->getId(),
                        'nickname' => $socialite->getNickname(),
                        'avatar' => $socialite->getAvatar(),
                        'token' => $socialite->token,
                        'token_secret' => $socialite->tokenSecret,
                        'refresh_token' => $socialite->refreshToken,
                        'expires_in' => $socialite->expiresIn,
                    ]],
                ]);
            }
        }
        else {
            $this->hasValidSignature = request()->hasValidSignature();
            $this->inputs = [
                ...$this->inputs,
                ...request()->query('fill', []),
            ];
        }
    }

    // submit
    public function submit() : mixed
    {
        $this->validateForm();

        if ($this->verify()) {
            return $this->register();
        }

        return null;
    }

    // verified
    public function verify() : bool
    {
        if (!config('atom.auth.verify')) return true;
        if ($this->hasValidSignature) return true;

        if ($this->verification = model('verification')
            ->where('email', get($this->inputs, 'email'))
            ->where(fn($q) => $q
                ->whereNull('expired_at')
                ->orWhere('expired_at', '>', now())
            )
            ->first()
        ) {
            if ($this->verification->code === get($this->inputs, 'verification')) {
                $this->clearVerificationCode();
                return true;
            }
            else if (get($this->inputs, 'verification')) {
                $this->popup('auth.alert.verification', 'alert', 'error');
            }
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
    }

    // register
    public function register($data = null) : mixed
    {
        $data = $data ?? $this->inputs;

        $user = model('user')->forceFill([
            'name' => get($data, 'name'),
            'email' => get($data, 'email'),
            'password' => bcrypt(get($data, 'password')),
            'tier' => 'signup',
            'data' => get($data, 'data'),
            'email_verified_at' => now(),
            'login_at' => now(),
        ]);

        $user->save();

        $user->signup()->create([
            'channel' => $this->utm ?? $this->ref ?? 'direct',
            'geo' => geoip()->getLocation()->toArray(),
            'agree_tnc' => get($data, 'agree_tnc'),
            'agree_promo' => get($data, 'agree_promo'),
        ]);

        return $this->registered($user->fresh());
    }

    // post registration
    public function registered($user) : mixed
    {
        auth()->login($user);

        Cookie::forget('_ref');

        event(new Registered($user->fresh()));

        return redirect($user->home());
    }
}