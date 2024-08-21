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

        $this->enabled($provider);

        if ($qs) session(['socialite-qs' => $qs]);

        return Socialite::driver($provider)->with($qs)->redirect();
    }

    // callback
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

    // check is enabled
    public function enabled($provider) : void
    {
        if (
            empty(config('services.'.$provider.'.client_id'))
            || empty(config('services.'.$provider.'.client_secret'))
        ) {
            abort(404);
        }
    }
}