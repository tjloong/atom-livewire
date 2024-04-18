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

        foreach (model('setting')->getSocialLogins() as $provider) {
            $id = settings(get($provider, 'name').'_client_id');
            $secret = settings(get($provider, 'name').'_client_secret');

            if ($id && $secret) {
                $provider = str(get($provider, 'name'))->slug()->toString();

                config([
                    'services.'.$provider => [
                        'client_id' => $id,
                        'client_secret' => $secret,
                        'redirect' => url('__auth/'.$provider.'/callback'),
                    ],
                ]);
            }
        }
    }
}