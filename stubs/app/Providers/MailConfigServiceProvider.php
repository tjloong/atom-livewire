<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('atom.static_site')) return;

        SiteSetting::configureSMTP();
    }
}