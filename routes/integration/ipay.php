<?php

$route = app('route');

$route->get('__ipay/checkout', 'IpayController@checkout')
    ->name('__ipay.checkout')
    ->withoutMiddleware('web');

$route->get('__ipay/redirect', 'IpayController@redirect')
    ->name('__ipay.redirect')
    ->withoutMiddleware('web');

$route->post('__ipay/webhook', 'IpayController@webhook')
    ->name('__ipay.webhook')
    ->withoutMiddleware('web');