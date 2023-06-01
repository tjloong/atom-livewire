<?php

define_route()->prefix('__stripe')->as('__stripe.')->group(function() {
    define_route('success', 'StripeController@success')->name('success');
    define_route('cancel', 'StripeController@cancel')->name('cancel');
    define_route('webhook', 'StripeController@webhook', 'post')->name('webhook');
});
