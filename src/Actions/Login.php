<?php

namespace Jiannius\Atom\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class Login
{
    public $user;
    public $passed = false;

    public function __construct(public $params)
    {
        //
    }

    public function run()
    {
        if ($this->user = get($this->params, 'user')) $this->passed = true;
        else $this->user = $this->getUser();

        if ($this->user) {
            if ($err = $this->tooManyAttempts()) return ['error' => $err];
            if (app()->environment('local')) $this->passed = true;


            if ($this->attempt()) {
                return redirect()->intended(get($this->params, 'redirect') ?? $this->user->home());
            }
        }

        return ['error' => t('auth.failed')];
    }

    public function getThrottleKey()
    {
        return strtolower($this->user->username ?? $this->user->email).'|'.request()->ip();
    }

    public function attempt()
    {
        if ($this->passed) {
            Auth::login($this->user);
        }
        else {
            $this->passed = Auth::attempt([
                'email' => get($this->params, 'data.email'),
                'password' => get($this->params, 'data.password'),
            ], get($this->params, 'data.remember'));
        }

        $key = $this->getThrottleKey();

        if ($this->passed) {
            RateLimiter::clear($key);

            $this->user->ping(true);
            request()->session()->regenerate();

            return true;
        }

        RateLimiter::hit($key);

        return false;
    }

    public function tooManyAttempts()
    {
        $key = $this->getThrottleKey();

        if (!RateLimiter::tooManyAttempts($key, 5)) return false;

        // event(new Illuminate\Auth\Events\Lockout($this));

        $seconds = RateLimiter::availableIn($key);

        return t('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);
    }

    public function getUser()
    {
        $email = get($this->params, 'data.email');

        if (!$email) return;

        $user = model('user')->loginable()->firstWhere('email', $email);

        if (!$user && model('user')->tableHasColumn('username')) {
            $user = model('user')->loginable()->firstWhere('username', $email);
        }

        return $user;
    }
}
