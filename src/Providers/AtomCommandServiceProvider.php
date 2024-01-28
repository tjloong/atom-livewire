<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\ServiceProvider;

class AtomCommandServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {        
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Jiannius\Atom\Console\CkeditorCommand::class,
                \Jiannius\Atom\Console\FontawesomeCommand::class,
                \Jiannius\Atom\Console\FootprintCommand::class,
                \Jiannius\Atom\Console\InitCommand::class,
                \Jiannius\Atom\Console\MigrateCommand::class,
                \Jiannius\Atom\Console\PublishCommand::class,
                \Jiannius\Atom\Console\RefreshCommand::class,
                \Jiannius\Atom\Console\SettingsCommand::class,
            ]);
        }
    }
}