<?php

define_route()->prefix('__ozopay')->as('__ozopay.')->group(function() {
    define_route('checkout', 'OzopayController@checkout')->name('checkout');
    define_route('redirect', 'OzopayController@redirect', 'post')->name('redirect');
    define_route('webhook', 'OzopayController@webhook', 'post')->name('webhook');
});
