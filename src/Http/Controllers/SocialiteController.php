<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect() : mixed
    {        
        $provider = request()->provider;
        $qs = request()->query();

        $this->enabled($provider);

        if ($qs) session(['socialite-qs' => $qs]);

        return Socialite::driver($provider)->with($qs)->redirect();
    }

    public function callback() : mixed
    {
        $provider = request()->provider;

        $this->enabled($provider);

        try {
            $user = Socialite::driver($provider)->user();
            $token = $user->token;
            $route = model('user')->where('email', $user->getEmail())->count() ? 'login' : 'register';
            $query = session('socialite-query', []);
    
            session()->forget('socialite-query');
    
            return to_route($route, array_merge(compact('token', 'provider'), $query));
        } catch (\Exception $e) {
            return to_route('web.home');
        }
    }

    public function enabled($provider) : void
    {
        $clientId = env(strtoupper($provider.'_client_id'));
        $clientSecret = env(strtoupper($provider.'_client_secret'));

        if (!$clientId || !$clientSecret) {
            abort(404);
        }

        config(['services.'.$provider => [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect' => route('socialite.callback', ['provider' => $provider]),
        ]]);
    }
}