<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Jiannius\Atom\Atom;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect() : mixed
    {        
        $provider = request()->provider;
        $qs = request()->query();

        if (Atom::action('configure-socialite', $provider)) {
            if ($qs) {
                session(['socialite-qs' => $qs]);
                return Socialite::driver($provider)->with($qs)->redirect();
            }

            return Socialite::driver($provider)->redirect();
        }

        abort(404);
    }

    public function callback() : mixed
    {
        $provider = request()->provider;

        if (Atom::action('configure-socialite', $provider)) {
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

        return to_route('web.home');
    }
}