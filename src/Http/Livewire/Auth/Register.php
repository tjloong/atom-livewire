<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Support\Facades\Cookie;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class Register extends Component
{
    use WithForm;

    public $ref;
    public $plan;
    public $price;
    public $token;
    public $provider;

    public $inputs = [
        'agree_tnc' => false,
        'agree_marketing' => true,
    ];

    protected $queryString = ['ref', 'token', 'provider', 'plan', 'price'];
    
    /**
     * Validation
     */
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
            'inputs.agree_marketing' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->ref && !$this->token && !$this->provider) return redirect('/');        
        if ($this->token && $this->provider) return $this->socialLogin();
    }

    /**
     * Social login
     */
    public function socialLogin(): mixed
    {
        rescue(function() use (&$user) {
            $user = Socialite::driver($this->provider)->userFromToken($this->token);
        });

        if (!$user) return null;

        if (model('user')->firstWhere('email', $user->getEmail())) {
            return redirect()->route('login', array_merge([
                'token' => $this->token,
                'provider' => $this->provider,
            ], request()->query()));
        }

        return $this->createUser([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => str()->snake($user->getName()).'_oauth',
            'agree_tnc' => true,
            'agree_marketing' => true,
            'email_verified_at' => now(),
            'data' => ['oauth' => [
                'provider' => $this->provider,
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'avatar' => $user->getAvatar(),
                'token' => $user->token,
                'token_secret' => $user->tokenSecret,
                'refresh_token' => $user->refreshToken,
                'expires_in' => $user->expiresIn,
            ]],
        ]);
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        return $this->createUser($this->inputs);
    }

    /**
     * Create user
     */
    public function createUser($inputs): mixed
    {
        $user = model('user')->forceFill([
            'name' => data_get($inputs, 'name'),
            'email' => data_get($inputs, 'email'),
            'password' => bcrypt(data_get($inputs, 'password')),
            'data' => array_merge(data_get($inputs, 'data', []), [
                'signup' => [
                    'geo' => geoip()->getLocation()->toArray(),
                    'channel' => $this->ref,
                    'agree_tnc' => data_get($inputs, 'agree_tnc'),
                    'agree_marketing' => data_get($inputs, 'agree_marketing'),
                ],
                'pref' => [
                    'timezone' => config('atom.timezone'), 
                    'locale' => head(config('atom.locales', [])) ?? null,
                ],
            ]),
            'email_verified_at' => data_get($inputs, 'email_verified_at'),
            'activated_at' => now(),
            'signup_at' => now(),
            'login_at' => now(),
        ]);

        $user->save();

        if (config('atom.auth.verify') && !data_get($inputs, 'email_verified_at')) {
            $user->sendEmailVerificationNotification();
        }

        auth()->login($user);

        // clear refcode
        Cookie::expire('_ref');

        return $this->registered($user->fresh());
    }

    /**
     * Post registration
     */
    public function registered($user): mixed
    {
        if (enabled_module('plans') && $this->plan && $this->price) {
            return redirect()->route('app.plan.subscription.create', [
                'plan' => $this->plan, 
                'price' => $this->price,
            ]);
        }

        return redirect()->route('app.onboarding');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('auth.register');
    }
}