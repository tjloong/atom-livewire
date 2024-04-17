<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // redirect
    public function redirect() : mixed
    {        
        $provider = request()->provider;
        $qs = request()->query();

        $this->configureService($provider, $qs);

        if ($qs) session(['socialite-qs' => $qs]);

        return Socialite::driver($provider)->with($qs)->redirect();
    }

    // callback
    public function callback() : mixed
    {
        $provider = request()->provider;
        
        $this->configureService($provider);

        $user = Socialite::driver($provider)->user();
        $token = $user->token;
        $route = model('user')->where('email', $user->getEmail())->count() ? 'login' : 'register';
        $query = session('socialite-query', []);

        session()->forget('socialite-query');

        return to_route($route, array_merge(compact('token', 'provider'), $query));
    }

    // configure service
    public function configureService($provider, $qs = []) : void
    {
        $id = settings($provider.'_client_id');
        $secret = settings($provider.'_client_secret');
        if (!$id || !$secret) abort(404);

        config([
            'services.'.$provider.'.client_id' => $id,
            'services.'.$provider.'.client_secret' => $secret,
            'services.'.$provider.'.redirect' => route('socialite.callback', [
                'provider' => $provider,
                ...$qs,
            ]),
        ]);
    }
}