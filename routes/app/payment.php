<?php

define_route()->prefix('payment')->as('app.payment.')->group(function() {
    define_route('listing', 'App\Payment\Listing')->name('listing');
    define_route('create', 'App\Payment\Create')->name('create');
    define_route('{paymentId}', 'App\Payment\Update')->name('update');
});
