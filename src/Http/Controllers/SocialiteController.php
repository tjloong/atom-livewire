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
        $query = request()->query();

        if (!$this->isProviderEnabled($provider)) abort(404);
        if ($query) session(['socialite-query' => $query]);

        return Socialite::driver($provider)->with($query)->redirect();
    }

    // callback
    public function callback() : mixed
    {
        $provider = request()->provider;
        $user = Socialite::driver($provider)->user();
        $token = $user->token;
        $route = model('user')->where('email', $user->getEmail())->count() ? 'login' : 'register';
        $query = session('socialite-query', []);

        session()->forget('socialite-query');

        return to_route($route, array_merge(compact('token', 'provider'), $query));
    }

    // check is provider enabled
    public function isProviderEnabled($provider) : bool
    {
        $id = settings($provider.'_client_id');
        $secret = settings($provider.'_client_secret');

        return $id && $secret;
    }
}