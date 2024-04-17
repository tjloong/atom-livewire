<?php

use Jiannius\Atom\Http\Controllers\SocialiteController;

$route = app('route');

$route->prefix('__auth/{provider}')->as('socialite.')->group(function() use ($route) {
    $route->get('redirect', [SocialiteController::class, 'redirect'])->name('redirect');
    $route->get('callback', [SocialiteController::class, 'callback'])->name('callback');
});
