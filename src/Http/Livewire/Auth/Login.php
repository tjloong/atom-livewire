<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email;
    public $password;
    public $remember;

    /**
     * Mount
     */
    public function mount()
    {
        if (request()->query('logout')) return $this->logout();
        else if ($user = auth()->user()) return redirect($this->redirectTo($user));
    }

    /**
     * Attempt login
     */
    public function login()
    {
        $user = model('user')
            ->where('email', $this->email)
            ->whereNotNull('activated_at')
            ->whereNull('blocked_at')
            ->whereHas('account', fn($q) => $q->whereNull('blocked_at'))
            ->first();

        if ($user) {
            if (app()->environment('local')) Auth::login($user);
            else {
                $this->ensureIsNotRateLimited();
        
                if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages(['email' => __('auth.failed')]);
                }

                RateLimiter::clear($this->throttleKey());
            }
        }
        else {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        $user->fill(['login_at' => now()])->saveQuietly();
        
        request()->session()->regenerate();
        
        return redirect()->intended($this->redirectTo($user));
    }

    /**
     * Logout
     */
    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('page');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // event(new Illuminate\Auth\Events\Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    private function throttleKey()
    {
        return str()->lower(request()->input('email')).'|'.request()->ip();
    }

    /**
     * Redirection
     * 
     * @return void
     */
    private function redirectTo($user)
    {
        if ($user->isAccountType('signup') && $user->account->status === 'new') {
            return route('app.onboarding.home');
        }

        return auth()->user()->home();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::auth.login')->layout('layouts.auth');
    }
}