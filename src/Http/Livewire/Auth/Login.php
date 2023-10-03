<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Laravel\Socialite\Facades\Socialite;

class Login extends Component
{
    use WithForm;
    use WithLoginMethods;

    public $throttlekey;

    public $inputs = [
        'username' => null,
        'password' => null,
        'remember' => false,
    ];

    // validation
    protected function validation(): array
    {
        return [
            'inputs.username' => ['required' => $this->usernameLabel.' is required.'],
            'inputs.password' => ['required' => 'Password is required.'],
        ];
    }

    // mount
    public function mount()
    {
        if (user()) return redirect(user()->home());
        // login using app key (root login)
        else if (
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

    // get username label property
    public function getUsernameLabelProperty(): string
    {
        return collect([
            $this->isLoginMethod('username') ? 'Username' : null,
            $this->isLoginMethod(['email', 'email-verified']) ? 'Email' : null,
        ])->filter()->join(' or ');
    }

    // get user
    public function getUser(): mixed
    {
        $username = data_get($this->inputs, 'username');
        $query = model('user')->whereNotNull('password')->whereNull('blocked_at');

        if ($this->isLoginMethod('username') && $this->isLoginMethod(['email', 'email-verified'])) {
            $query->where(fn($q) => $q
                ->where('username', $username)
                ->orWhere('email', $username)
            );
        }
        else if ($this->isLoginMethod('username')) {
            $query->where('username', $username);
        }
        else if ($this->isLoginMethod(['email', 'email-verified'])) {
            $query->where('email', $username);
        }

        return $query->first();
    }

    // submit
    public function submit($user = null): mixed
    {
        if ($user) Auth::login($user);
        else {
            $this->validateForm();

            if ($user = $this->getUser()) {
                if (app()->environment('local')) Auth::login($user);
                else if ($err = $this->tooManyAttempts()) return $this->addError('email', $err);
                else if (!$this->login($user)) return $this->addError('email', __('auth.failed'));
            }
            else return $this->addError('email', __('auth.failed'));
        }

        $user->fill(['login_at' => now()])->saveQuietly();
        
        request()->session()->regenerate();
        
        return redirect()->intended($this->redirectTo($user));        
    }

    // attempt login
    public function login($user = null): mixed
    {
        $attempt = false;
        $username = data_get($this->inputs, 'username');
        $password = data_get($this->inputs, 'password');
        $remember = data_get($this->inputs, 'remember');

        if (!$attempt && $this->isLoginMethod('username')) {
            $attempt = Auth::attempt(compact('username', 'password'), $remember);
        }

        if (!$attempt && $this->isLoginMethod(['email', 'email-verified'])) {
            $attempt = Auth::attempt(['email' => $username, 'password' => $password], $remember);
        }

        $this->throttlekey = str()->lower($username).'|'.request()->ip();

        if ($attempt) RateLimiter::clear($this->throttlekey);
        else RateLimiter::hit($this->throttlekey);

        return $attempt;
    }

    // check has too many attempts
    public function tooManyAttempts(): mixed
    {
        if (!RateLimiter::tooManyAttempts($this->throttlekey, 5)) return false;

        // event(new Illuminate\Auth\Events\Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttlekey);

        return __('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);
    }

    // redirection
    public function redirectTo($user): string
    {
        return $user->home();
    }
}