<?php

define_route()->prefix('shop')->as('web.shop.')->group(function() {
    define_route('/', 'Web\Shop\Listing')->name('listing');
    define_route('checkout', 'Web\Shop\Checkout')->name('checkout');
    define_route('{slug}', 'Web\Shop\Product')->name('product');
});

