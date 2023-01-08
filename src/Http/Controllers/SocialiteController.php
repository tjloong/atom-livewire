<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect
     */
    public function redirect()
    {
        if (request()->query()) {
            session(['socialite-query' => request()->query()]);
        }

        return Socialite::driver(request()->provider)->with(request()->query())->redirect();
    }

    /**
     * Callback
     */
    public function callback()
    {
        $provider = request()->provider;
        $user = Socialite::driver($provider)->user();
        $route = model('user')->firstWhere('email', $user->getEmail())
            ? 'login'
            : 'register';

        $query = session('socialite-query', []);
        session()->forget('socialite-query');

        return redirect()->route($route, array_merge([
            'token' => $user->token,
            'provider' => $provider,
        ], $query));
    }
}