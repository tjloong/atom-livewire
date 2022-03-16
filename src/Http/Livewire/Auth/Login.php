<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
        if (request()->query('logout')) {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        else if (auth()->check()) {
            return redirect($this->redirectTo());
        }
    }

    /**
     * Attempt login
     */
    public function login()
    {
        $user = model('user')
            ->where('email', $this->email)
            ->where('is_active', true)
            ->where(fn($q) => $q
                ->doesntHave('account')
                ->orWhereHas('account', fn($q) => $q->whereNull('deleted_at')->whereNull('blocked_at'))
            )
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

        request()->session()->regenerate();
        
        return redirect()->intended($this->redirectTo());
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
        return Str::lower(request()->input('email')).'|'.request()->ip();
    }

    /**
     * Redirection
     * 
     * @return void
     */
    private function redirectTo()
    {
        if (Route::has('app.home')) return route('app.home');

        return '/';
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::auth.login')->layout('layouts.auth');
    }
}