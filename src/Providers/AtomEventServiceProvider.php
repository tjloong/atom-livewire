<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Jiannius\Atom\Listeners\Notilog;

class AtomEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NotificationSending::class => [
            Notilog::class,
        ],
        NotificationSent::class => [
            Notilog::class,
        ],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        parent::boot();
    }
}