<?php

$route = app('route');

// stripe
$route->get('__stripe/success', 'StripeController@success')
    ->name('__stripe.success')
    ->withoutMiddleware('web');

$route->get('__stripe/cancel', 'StripeController@cancel')
    ->name('__stripe.cancel')
    ->withoutMiddleware('web');

$route->get('__stripe/webhook', 'StripeController@webhook', 'post')
    ->name('__stripe.webhook')
    ->withoutMiddleware('web');

// revenue monster
$route->get('__revenue-monster/redirect', 'RevenueMonsterController@redirect')
    ->name('__revenue-monster.redirect')
    ->withoutMiddleware('web');

$route->post('__revenue-monster/webhook', 'RevenueMonsterController@webhook')
    ->name('__revenue-monster.webhook')
    ->withoutMiddleware('web');

// define_route()->prefix('__ozopay')->as('__ozopay.')->group(function() {
//     define_route('checkout', 'OzopayController@checkout')->name('checkout');
//     define_route('redirect', 'OzopayController@redirect', 'post')->name('redirect');
//     define_route('webhook', 'OzopayController@webhook', 'post')->name('webhook');
// });

// define_route()->prefix('__ipay')->as('__ipay.')->group(function() {
//     define_route('checkout', 'IpayController@checkout')->name('checkout');
//     define_route('redirect', 'IpayController@redirect', 'post')->name('redirect');
//     define_route('webhook', 'IpayController@webhook', 'post')->name('webhook');
// });

// define_route()->prefix('__gkash')->as('__gkash.')->group(function() {
//     define_route('checkout', 'GkashController@checkout')->name('checkout');
//     define_route('redirect', 'GkashController@redirect', 'post')->name('redirect');
//     define_route('webhook', 'GkashController@webhook', 'post')->name('webhook');
// });