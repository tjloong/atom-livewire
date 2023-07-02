<?php

define_route()->prefix('plan')->as('plan.')->group(function() {
    define_route('listing', 'App\Plan\Listing')->name('listing');
    define_route('create', 'App\Plan\Create')->name('create');

    define_route()->prefix('subscription')->as('subscription.')->group(function() {
        define_route('listing', 'App\Plan\Subscription\Listing')->name('listing');
        define_route('create', 'App\Plan\Subscription\Create')->name('create');
        define_route('{subscriptionId}', 'App\Plan\Subscription\Update')->name('update');
    });

    define_route()->prefix('payment')->as('payment.')->group(function() {
        define_route('listing', 'App\Plan\Payment\Listing')->name('listing');
        define_route('create', 'App\Plan\Payment\Create')->name('create');
        define_route('{paymentId}', 'App\Plan\Payment\Update')->name('update');                    
    });

    define_route()->prefix('{planId}')->group(function() {
        define_route()->prefix('price')->as('price.')->group(function() {
            define_route('listing', 'App\Plan\Price\Listing')->name('listing');
            define_route('create', 'App\Plan\Price\Create')->name('create');
            define_route('{priceId}', 'App\Plan\Price\Update')->name('update');
        });

        define_route('{tab?}', 'App\Plan\Update')->name('update');
    });
});
