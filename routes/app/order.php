<?php

define_route()->prefix('order')->as('app.order.')->group(function() {
    define_route('listing', 'App\Order\Listing')->name('listing');
    define_route('create', 'App\Order\Create')->name('create');
    define_route('{orderId}', 'App\Order\Update')->name('update');
});
