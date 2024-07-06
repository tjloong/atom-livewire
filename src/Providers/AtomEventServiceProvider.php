<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AtomEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Illuminate\Mail\Events\MessageSending::class => [
            \Jiannius\Atom\Listeners\Sendmail::class,
        ],
        \Illuminate\Mail\Events\MessageSent::class => [
            \Jiannius\Atom\Listeners\Sendmail::class,
        ],
        // TODO: deprecate notilogs
        \Illuminate\Notifications\Events\NotificationSending::class => [
            \Jiannius\Atom\Listeners\Notilog::class,
        ],
        \Illuminate\Notifications\Events\NotificationSent::class => [
            \Jiannius\Atom\Listeners\Notilog::class,
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