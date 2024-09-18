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

    public $user;
    public $redirect;
    public $throttlekey;
    
    public $inputs = [
        'email' => null,
        'password' => null,
        'remember' => false,
    ];
    
    private $socialiteUser;

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
        // socialite login (skip error from socialite)
        else if (
            ($token = request()->query('token'))
            && ($provider = request()->query('provider'))
            && ($user = rescue(fn() => optional(Socialite::driver($provider))->userFromToken($token)))
        ) {
            $this->socialiteUser = $user;

            $this->fill([
                'inputs.email' => $user->getEmail()
            ]);

            return $this->submit();
        }
        else {
            $this->fill([
                'redirect' => request()->query('redirect'),
                'inputs.email' => request()->query('email') ?? request()->query('fill.email'),
            ]);
        }
    }

    // get user
    public function getUser() : void
    {
        $this->user = model('user')
            ->whereNotNull('password')
            ->whereNull('blocked_at')
            ->where('email', get($this->inputs, 'email'))
            ->first();
    }

    // submit
    public function submit() : mixed
    {
        if ($this->socialiteUser) {
            $this->getUser();
            
            if (!$this->user) return to_route('register', request()->query());
            
            return $this->login();
        }
        else {
            $this->validateForm();
            $this->getUser();

            if (!$this->user) return $this->failed();

            return $this->login();
        }
    }

    // login
    public function login(): mixed
    {
        if ($err = $this->tooManyAttempts()) return $this->failed($err);

        if (app()->environment('local')) {
            Auth::login($this->user);
            $this->user->ping(true);
            request()->session()->regenerate();
            return $this->success();
        }

        $email = get($this->inputs, 'email');
        $password = get($this->inputs, 'password');
        $remember = get($this->inputs, 'remember');
        $attempt = Auth::attempt(compact('email', 'password'), $remember);

        $this->throttlekey = str()->lower($email).'|'.request()->ip();

        if ($attempt) {
            RateLimiter::clear($this->throttlekey);
            $this->user->ping(true);
            request()->session()->regenerate();
            return $this->success();
        }

        RateLimiter::hit($this->throttlekey);

        return $this->failed();
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
    public function success() : mixed
    {
        return redirect()->intended(
            $this->redirect ?? $this->user->home()
        );
    }

    // failed
    public function failed($e = null) : mixed
    {
        return $this->addError('failed', $e ?? tr('auth.alert.failed'));
    }
}