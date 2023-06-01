<?php

define_route()->prefix('product')->as('app.product.')->group(function() {
    define_route('listing', 'App\Product\Listing')->name('listing');
    define_route('create', 'App\Product\Create')->name('create');
    define_route('{productId}/{tab?}', 'App\Product\Update')->name('update')->whereNumber('productId');
    
    // product variant
    define_route()->prefix('variant')->as('variant.')->group(function() {
        define_route('create/{productId}', 'App\Product\Variant\Create')->name('create');
        define_route('{variantId}', 'App\Product\Variant\Update')->name('update');
    });
});
