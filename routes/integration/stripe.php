<?php

$route = app('route');

$route->get('__stripe/success', 'StripeController@success')
    ->name('__stripe.success')
    ->withoutMiddleware('web');

$route->get('__stripe/cancel', 'StripeController@cancel')
    ->name('__stripe.cancel')
    ->withoutMiddleware('web');

$route->get('__stripe/webhook', 'StripeController@webhook', 'post')
    ->name('__stripe.webhook')
    ->withoutMiddleware('web');