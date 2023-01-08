<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class Register extends Component
{
    public $ref;
    public $plan;
    public $price;
    public $token;
    public $provider;

    public $form = [
        'agree_tnc' => false,
        'agree_marketing' => true,
    ];

    protected $queryString = ['ref', 'token', 'provider', 'plan', 'price'];
    
    /**
     * Validation rules
     */
    protected function rules() 
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|unique:users,email',
            'form.password' => 'required|min:8',
            'form.agree_tnc' => 'accepted',
            'form.agree_marketing' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'form.name.required' => __('Name is required.'),
            'form.name.string' => __('Invalid name.'),
            'form.name.max' => __('Name has exceeded maximum characters allowed.'),
            'form.email.required' => __('Login email is required.'),
            'form.email.email' => __('Invalid email.'),
            'form.email.unique' => __('This login email has been taken.'),
            'form.password.required' => __('Login password is required.'),
            'form.password.min' => __('Login password must be at least 8 characters.'),
            'form.agree_tnc.accepted' => __('Please accept the terms and conditions to proceed.'),
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
    public function socialLogin()
    {
        rescue(function() use (&$user) {
            $user = Socialite::driver($this->provider)->userFromToken($this->token);
        });

        if (!$user) return;

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
        ], false);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        return $this->createUser($this->form);
    }

    /**
     * Create user
     */
    public function createUser($inputs, $verify = true)
    {
        $account = model('account')->create([
            'type' => 'signup',
            'name' => data_get($inputs, 'name'),
            'email' => data_get($inputs, 'email'),
            'agree_tnc' => data_get($inputs, 'agree_tnc'),
            'agree_marketing' => data_get($inputs, 'agree_marketing'),
            'data' => [
                'register_geo' => geoip()->getLocation()->toArray(),
                'register_channel' => $this->ref,
            ],
        ]);

        $account->settings()->create([
            'timezone' => config('atom.timezone'), 
            'locale' => head(config('atom.locales', [])) ?? null,
        ]);

        $user = $account->users()->create([
            'name' => data_get($inputs, 'name'),
            'email' => data_get($inputs, 'email'),
            'password' => bcrypt(data_get($inputs, 'password')),
            'data' => data_get($inputs, 'data'),
            'activated_at' => now(),
            'login_at' => now(),
        ]);

        if (config('atom.accounts.verify') && $verify) $user->sendEmailVerificationNotification();

        auth()->login($user);

        $this->registered($user->fresh());

        return redirect($this->redirectTo($user->fresh()));
    }

    /**
     * Post registration
     */
    public function registered($user)
    {
        // clear refcode
        Cookie::expire('_ref');
    }

    /**
     * Redirect after registration
     */
    public function redirectTo($user)
    {
        if (enabled_module('plans') && $this->plan && $this->price) {
            return route('app.billing.checkout', [
                'plan' => $this->plan, 
                'price' => $this->price,
            ]);
        }
        else return route('app.onboarding.home');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('auth.register');
    }
}