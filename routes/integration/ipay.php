<?php

$route = app('route');

$route->get('checkout', 'IpayController@checkout')
    ->name('__ipay.checkout')
    ->withoutMiddleware('web');

$route->get('redirect', 'IpayController@redirect')
    ->name('__ipay.redirect')
    ->withoutMiddleware('web');

$route->post('webhook', 'IpayController@webhook')
    ->name('__ipay.webhook')
    ->withoutMiddleware('web');