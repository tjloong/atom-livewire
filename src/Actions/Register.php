<?php

namespace Jiannius\Atom\Actions;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class Register
{
    public $user;
    public $signup;

    public function __construct(public $params)
    {
        //
    }

    public function run()
    {
        $this->createUser();
        $this->createSignup();

        Auth::login($this->user);

        event(new Registered($this->user->fresh()));

        return redirect(get($this->params, 'redirect') ?? $this->user->home());
    }

    public function createUser() : void
    {
        $this->user = User::firstOrNew(['email' => get($this->params, 'data.email')], [
            'name' => get($this->params, 'data.name'),
            'tier' => 'signup',
            'data' => get($this->params, 'data.data'),
            'email_verified_at' => now(),
            'login_at' => now(),
        ]);

        if ($this->user->exists) return;

        $this->user->forceFill([
            'password' => bcrypt(get($this->params, 'data.password')),
        ]);

        $this->user->save();
    }

    public function createSignup() : void
    {
        $this->signup = $this->user->signup
            ?? $this->user->signup()->create([
                'refcode' => get($this->params, 'refcode'),
                'utm' => get($this->params, 'utm'),
                'geo' => geoip()->getLocation()->toArray(),
                'agree_tnc' => get($this->params, 'data.agree_tnc'),
                'agree_promo' => get($this->params, 'data.agree_promo'),
            ]);
    }
}
