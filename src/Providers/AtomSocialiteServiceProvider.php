<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\ServiceProvider;

class AtomSocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {        
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('atom.static_site')) return;
        if ($this->app->runningInConsole()) return;

        foreach (config('atom.accounts.login', []) as $provider) {
            $clientId = site_settings($provider.'_client_id');
            $clientSecret = site_settings($provider.'_client_secret');

            if ($clientId && $clientSecret) {
                $provider = str($provider)->slug()->toString();

                config([
                    'services.'.$provider => [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'redirect' => "/__auth/$provider/callback",
                    ],
                ]);
            }
        }
    }
}