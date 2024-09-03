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

    public $redirect;
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
                return $this->submit($user);
            }
            // socialite login (skip error from socialite)
            else if (
                ($token = request()->query('token'))
                && ($provider = request()->query('provider'))
                && ($socialite = rescue(fn() => Socialite::driver($provider)->userFromToken($token)))
                && ($user = model('user')->firstWhere('email', $socialite->getEmail()))
            ) {
                return $this->submit($user);
            }

            $this->fill([
                'redirect' => request()->query('redirect'),
                'inputs.email' => request()->query('email') ?? request()->query('fill.email'),
            ]);
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
        if (!$user) {
            $this->validateForm();
            $user = $this->getUser();
            if (!$user) return $this->failed();
        }

        return $this->login($user);        
    }

    // login
    public function login($user): mixed
    {
        if ($err = $this->tooManyAttempts()) return $this->failed($err);

        if (app()->environment('local')) {
            Auth::login($user);
            $user->ping(true);
            request()->session()->regenerate();
            return $this->success($user);
        }

        $email = get($this->inputs, 'email');
        $password = get($this->inputs, 'password');
        $remember = get($this->inputs, 'remember');
        $attempt = Auth::attempt(compact('email', 'password'), $remember);

        $this->throttlekey = str()->lower($email).'|'.request()->ip();

        if ($attempt) {
            RateLimiter::clear($this->throttlekey);
            $user->ping(true);
            request()->session()->regenerate();
            return $this->success($user);
        }

        RateLimiter::hit($this->throttlekey);
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

    // success
    public function success($user) : mixed
    {
        return redirect()->intended(
            $this->redirect ?? $user->home()
        );
    }

    // failed
    public function failed($e = null) : mixed
    {
        return $this->addError('failed', $e ?? tr('auth.alert.failed'));
    }
}