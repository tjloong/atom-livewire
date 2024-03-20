<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Laravel\Socialite\Facades\Socialite;

class Login extends Component
{
    use WithForm;

    public $throttlekey;

    public $inputs = [
        'email' => null,
        'password' => null,
        'remember' => false,
    ];

    // validation
    protected function validation() : array
    {
        return [
            'inputs.email' => ['required' => 'Email is required.'],
            'inputs.password' => ['required' => 'Password is required.'],
        ];
    }

    // mount
    public function mount()
    {
        if (user()) {
            return redirect(user()->home());
        }
        else {    
            // login using app key (root login)
            if (
                ($appkey = request()->query('appkey'))
                && $appkey === config('app.key')
                && ($user = model('user')->oldest()->firstWhere('tier', 'root'))
            ) {
                $this->submit($user);
            }
            // socialite login (skip error from socialite)
            else if (
                ($token = request()->query('token'))
                && ($provider = request()->query('provider'))
                && ($socialite = rescue(fn() => Socialite::driver($provider)->userFromToken($token)))
                && ($user = model('user')->firstWhere('email', $socialite->getEmail()))
            ) {
                $this->submit($user);
            }
        }
    }

    // get user
    public function getUser() : mixed
    {
        return model('user')
            ->whereNotNull('password')
            ->whereNull('blocked_at')
            ->where('email', get($this->inputs, 'email'))
            ->first();
    }

    // submit
    public function submit($user = null) : mixed
    {
        if ($user) Auth::login($user);
        else {
            $this->validateForm();

            if ($user = $this->getUser()) {
                if (app()->environment('local')) Auth::login($user);
                else if ($err = $this->tooManyAttempts()) return $this->addError('email', $err);
                else if (!$this->login($user)) return $this->addError('email', tr('auth.alert.failed'));
            }
            else return $this->addError('email', tr('auth.alert.failed'));
        }

        $this->loggedIn($user);
        
        return redirect()->intended($this->redirectTo($user));        
    }

    // attempt login
    public function login($user = null) : mixed
    {
        $email = get($this->inputs, 'email');
        $password = get($this->inputs, 'password');
        $remember = get($this->inputs, 'remember');
        $attempt = Auth::attempt(compact('email', 'password'), $remember);

        $this->throttlekey = str()->lower($email).'|'.request()->ip();

        if ($attempt) RateLimiter::clear($this->throttlekey);
        else RateLimiter::hit($this->throttlekey);

        return $attempt;
    }

    // logged in
    public function loggedIn($user) : void
    {
        $user->ping(true);
        request()->session()->regenerate();
    }

    // check has too many attempts
    public function tooManyAttempts() : mixed
    {
        if (!RateLimiter::tooManyAttempts($this->throttlekey, 5)) return false;

        // event(new Illuminate\Auth\Events\Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttlekey);

        return tr('auth.alert.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);
    }

    // redirection
    public function redirectTo($user) : string
    {
        return $user->home();
    }
}