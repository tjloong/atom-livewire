<?php

define_route()->prefix('shipping')->as('app.shipping.')->group(function() {
    define_route('listing', 'App\Shipping\Listing')->name('listing');
    define_route('create', 'App\Shipping\Create')->name('create');
    define_route('{rateId}', 'App\Shipping\Update')->name('update');
});
