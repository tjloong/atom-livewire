<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\ServiceProvider;

class AtomBreadcrumbsServiceProvider extends ServiceProvider
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
        $this->app->singleton('breadcrumbs', function () {
            return new \Jiannius\Atom\Services\Breadcrumbs;
        });
        
        $breadcrumbs = base_path('routes/breadcrumbs.php');
        
        if (file_exists($breadcrumbs)) $this->loadRoutesFrom($breadcrumbs);
    }
}