<?php

define_route()->prefix('coupon')->as('app.coupon.')->group(function() {
    define_route('listing', 'App\Coupon\Listing')->name('listing');
    define_route('create', 'App\Coupon\Create')->name('create');
    define_route('{couponId}', 'App\Coupon\Update')->name('update');
});
