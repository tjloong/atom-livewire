<?php

namespace Jiannius\Atom\Services;

class Route
{
    // __call
    public function __call($name, $arguments)
    {
        if ($name === 'has') {
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
        $this->post('__recaptcha', 'RecaptchaController')->withoutMiddleware('web')->name('__recaptcha');
    }

    // create integration routes
    public function integration($finexus = false, $gkash = false, $ozopay = false) : void
    {
        if ($finexus) {
            $this->prefix('__finexus')->as('__finexus')->withoutMiddleware('web')->group(function () {
                $this->get('success', 'FinexusController@success')->name('.success');
                $this->get('failed', 'FinexusController@failed')->name('.failed');
                $this->get('cancel', 'FinexusController@cancel')->name('.cancel');
                $this->get('query', 'FinexusController@query')->name('.query');
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
}
