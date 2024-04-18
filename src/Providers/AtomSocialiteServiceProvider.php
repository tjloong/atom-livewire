<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\ServiceProvider;

class AtomSocialiteServiceProvider extends ServiceProvider
{
    public $providers = [
        'bitbucket',
        'facebook',
        'github',
        'gitlab',
        'google',
        'linkedin_openid',
        'slack',
        'twitter',
        'twitter_oauth_2',
    ];

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

        foreach ($this->providers as $provider) {
            $id = settings($provider.'_client_id');
            $secret = settings($provider.'_client_secret');
            
            if ($id && $secret) {
                $provider = str($provider)->slug()->toString();

                config([
                    'services.'.$provider => [
                        'client_id' => $id,
                        'client_secret' => $secret,
                        'redirect' => route('socialite.callback', $provider),
                    ],
                ]);
            }
        }
    }
}