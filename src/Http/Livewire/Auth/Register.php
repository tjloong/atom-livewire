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

    public $inputs = [
        'agree_tnc' => false,
        'agree_promo' => true,
    ];

    protected $queryString = ['ref', 'utm', 'token', 'provider', 'plan'];
    
    // validation
    protected function validation(): array
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
    }

    // submit
    public function submit(): mixed
    {
        $this->validateForm();

        return $this->register();
    }

    // register
    public function register($data = null): mixed
    {
        $data = $data ?? $this->inputs;

        $user = model('user')->forceFill([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => bcrypt(data_get($data, 'password')),
            'data' => data_get($data, 'data'),
            'email_verified_at' => data_get($data, 'email_verified_at'),
            'login_at' => now(),
        ]);

        $user->save();

        $user->signup()->create([
            'channel' => $this->utm ?? $this->ref ?? 'direct',
            'geo' => geoip()->getLocation()->toArray(),
            'agree_tnc' => data_get($data, 'agree_tnc'),
            'agree_promo' => data_get($data, 'agree_promo'),
        ]);

        return $this->registered($user->fresh());
    }

    // post registration
    public function registered($user): mixed
    {
        auth()->login($user);

        Cookie::forget('_ref');

        event(new Registered($user->fresh()));

        return to_route($user->home());
    }
}