<?php

define_route()->prefix('__ipay')->as('__ipay.')->group(function() {
    define_route('checkout', 'IpayController@checkout')->name('checkout');
    define_route('redirect', 'IpayController@redirect', 'post')->name('redirect');
    define_route('webhook', 'IpayController@webhook', 'post')->name('webhook');
});
