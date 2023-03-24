<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class Login extends Component
{
    use WithForm;

    public $email;
    public $password;
    public $remember;
    public $socialUser;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'email' => ['required' => 'Email is required.'],
            'password' => ['required' => 'Password is required.'],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (request()->query('logout')) return $this->logout();
        else if (user()) return redirect($this->redirectTo(user()));
        else {
            rescue(function() {
                $token = request()->query('token');
                $provider = request()->query('provider');

                if ($token && $provider && ($this->socialUser = Socialite::driver($provider)->userFromToken($token))) {
                    $this->email = $this->socialUser->getEmail();
                    $this->login();
                }
            });
        }
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();
        $this->login();
    }

    /**
     * Attempt login
     */
    public function login(): mixed
    {
        $user = model('user')
            ->where('email', $this->email)
            ->whereNotNull('activated_at')
            ->whereNull('blocked_at')
            ->first();

        if ($user) {
            if (app()->environment('local') || $this->socialUser) Auth::login($user);
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
    public function logout(): mixed
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Ensure the login request is not rate limited.
     */
    private function ensureIsNotRateLimited(): void
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
     */
    private function throttleKey(): string
    {
        return str()->lower(request()->input('email')).'|'.request()->ip();
    }

    /**
     * Redirection
     */
    private function redirectTo($user): string
    {
        return $user->status === 'new'
            ? route('app.onboarding')
            : $user->home();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('auth.login');
    }
}