<?php

namespace Jiannius\Atom\Services;

class Route
{
    // __call
    public function __call($name, $arguments)
    {
        if ($name === 'channel') {
            return $this->channel($name, $arguments);
        }
        else if ($name === 'has') {
            return \Illuminate\Support\Facades\Route::has($arguments);
        }
        else if ($name === 'is') {
            return $this->isCurrentRoute(...$arguments);
        }
        else if ($name === 'current') {
            return optional(request()->route())->getName();
        }
        else if (in_array($name, ['get', 'post', 'put', 'patch', 'delete', 'options'])) {
            $path = $arguments[0];
            $callback = $this->callback($arguments[1]);
            $arguments = [$path, $callback];
        }
        else if ($name === 'match') {
            $methods = $arguments[0];
            $path = $arguments[1];
            $callback = $this->callback($arguments[2]);
            $arguments = [$methods, $path, $callback];
        }

        return \Illuminate\Support\Facades\Route::$name(...$arguments);
    }

    // get callback
    public function callback($name) : mixed
    {
        if (is_string($name)) {
            if (str($name)->is('*Controller*')) {
                if (str($name)->is('*@*')) [$postfix, $method] = explode('@', $name);
                else $postfix = $name;

                $class = collect([
                    'App\Http\Controllers\\'.$postfix,
                    'Jiannius\Atom\Http\Controllers\\'.$postfix,
                ])->first(fn($ns) => class_exists($ns));

                return isset($method) ? [$class, $method] : $class;
            }
            else {
                return collect([
                    'App\Http\Livewire\\'.$name,
                    'App\Http\Livewire\\'.$name.'\Index',
                    'Jiannius\Atom\Http\Livewire\\'.$name,
                    'Jiannius\Atom\Http\Livewire\\'.$name.'\Index',
                ])->first(fn($ns) => class_exists($ns));
            }
        }
        else return $name;
    }

    // broadcast channel
    public function channel($name, $arguments = null)
    {
        if ($name === 'notification' && !$arguments) {
            return \Illuminate\Support\Facades\Broadcast::channel('notification.{id}', function ($user, $id) {
                return $user && $user->id === model('user')->find($id)?->id;
            });
        }
        else {
            return \Illuminate\Support\Facades\Broadcast::channel($name, $arguments);
        }
    }

    // check is current route
    public function isCurrentRoute(...$name)
    {
        $routes = count($name) > 1 ? $name : head($name);
        $currentRoute = optional(request()->route())->getName();
    
        return $routes
            ? collect($routes)->contains(fn($val) => str($currentRoute)->is($val))
            : false;
    }

    // create default route
    public function default() : void
    {
        $this->get('__sitemap', 'SitemapController')->name('__sitemap');
        $this->post('__recaptcha', 'RecaptchaController')->withoutMiddleware('web')->name('__recaptcha');
        $this->post('__select/get', 'SelectController@get')->name('__select.get');

        $this->post('__select', function() {
            return app('select')
                ->filters(request()->filters)
                ->selected(request()->selected)
                ->get(request()->name);
        })->name('__select');

        $this->prefix('__file')->as('__file')->group(function() {
            $this->post('upload', 'FileController@upload')->name('.upload');
            $this->get('{name?}', 'FileController');
        });

        // icons
        $this->get('__icons.js', function () {
            return \Jiannius\Atom\Services\Icon::jsResponse();
        })->name('__icons.js');

        // lang
        $this->get('__lang/{lang?}', function ($lang = null) {
            session()->put('__lang', $lang ?? user()?->settings('locale') ?? config('atom.locale') ?? 'en');
            return redirect(user()?->home() ?? '/');
        })->name('__lang');
    
        $this->get('__lang.js', function () {
            return \Jiannius\Atom\Services\Lang::jsResponse();
        })->withoutMiddleware('web')->name('__lang.js');
    }

    // create auth routes
    public function auth($login = true, $password = true, $register = false, $socialite = false) : void
    {
        if ($login) {
            $this->get('login', 'Auth\Login')->name('login');
            $this->get('logout', 'Auth\Logout')->middleware('auth')->name('logout');
        }

        if ($password) {
            $this->get('reset-password', 'Auth\ResetPassword')->name('password.reset');
            $this->get('forgot-password', 'Auth\ForgotPassword')->middleware('guest')->name('password.forgot');
        }

        if ($register) {
            $this->get('register', 'Auth\Register')->middleware('guest')->name('register');
        }

        if ($socialite) {
            $this->get('__auth/{provider}/redirect', 'SocialiteController@redirect')->name('socialite.redirect');
            $this->get('__auth/{provider}/callback', 'SocialiteController@callback')->name('socialite.callback');
        }
    }

    // create integration routes
    public function integration($senangpay = false, $finexus = false, $stripe = false, $ipay = false, $gkash = false, $ozopay = false) : void
    {
        if ($senangpay) {
            $this->prefix('__senangpay')->as('__senangpay')->withoutMiddleware('web')->group(function () {
                $this->get('redirect', 'SenangpayController@redirect')->name('.redirect');
                $this->get('recurring', 'SenangpayController@recurring')->name('.recurring');
                $this->post('webhook', 'SenangpayController@webhook')->name('.webhook');
            });
        }

        if ($finexus) {
            $this->prefix('__finexus')->as('__finexus')->withoutMiddleware('web')->group(function () {
                $this->get('success', 'FinexusController@success')->name('.success');
                $this->get('failed', 'FinexusController@failed')->name('.failed');
                $this->get('cancel', 'FinexusController@cancel')->name('.cancel');
                $this->get('query', 'FinexusController@query')->name('.query');
            });
        }

        if ($stripe) {
            $this->prefix('__stripe')->as('__stripe')->withoutMiddleware('web')->group(function () {
                $this->get('success', 'StripeController@success')->name('.success');
                $this->get('cancel', 'StripeController@cancel')->name('.cancel');
                $this->post('webhook', 'StripeController@webhook')->name('.webhook');
            });
        }

        if ($ipay) {
            $this->prefix('__ipay')->as('__ipay')->withoutMiddleware('web')->group(function () {
                $this->post('redirect', 'IpayController@redirect')->name('.redirect');
                $this->post('webhook', 'IpayController@webhook')->name('.webhook');
                $this->get('checkout', function () {
                    return \Jiannius\Atom\Services\Ipay::getCheckoutForm();
                })->name('.checkout');
            });
        }

        if ($gkash) {
            $this->prefix('__gkash')->as('__gkash')->withoutMiddleware('web')->group(function () {
                $this->get('checkout', 'GkashController@checkout')->name('.checkout');
                $this->post('redirect', 'GkashController@redirect')->name('.redirect');
                $this->post('webhook', 'GkashController@webhook')->name('.webhook');
            });
        }

        if ($ozopay) {
            $this->prefix('__ozopay')->as('__ozopay')->withoutMiddleware('web')->group(function () {
                $this->get('checkout', 'OzopayController@checkout')->name('.checkout');
                $this->post('redirect', 'OzopayController@redirect')->name('.redirect');
                $this->post('webhook', 'OzopayController@webhook')->name('.webhook');
            });
        }
    }

    // create onboarding routes
    public function onboarding() : void
    {
        $this->prefix('onboarding')->as('onboarding')->middleware('auth')->group(function() {
            $this->get('/', 'Onboarding');
            $this->get('completed', 'Onboarding\Completed')->name('.completed');
        });
    }

    // create referral route
    public function referral() : void
    {
        $this->get('referral/{code}', function($code) {
            return to_route('register', [
                'refcode' => $code,
                'utm_campaign' => 'Referral_Invite',
                'utm_medium' => 'Referral_Program',
                'utm_source' => 'CopyPaste',
                ...request()->query(),
            ]);
        })->name('referral');
    }

    // create wrapper for app
    public function app($closure) : mixed
    {
        return $this->prefix('app')->as('app')->middleware('auth')->group($closure);
    }

    // create wrapper for root
    public function root($closure) : mixed
    {
        return $this->prefix('root')->as('root')->middleware('auth')->group($closure);
    }

    // create wrapper for web
    public function web($closure) : mixed
    {
        return $this->as('web')->group($closure);
    }
}