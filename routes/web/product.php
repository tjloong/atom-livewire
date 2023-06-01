<?php

define_route()->prefix('product')->as('web.product.')->group(function() {
    define_route('/', 'Web\Product\Listing')->name('listing');
    define_route('{slug}', 'Web\Product\View')->name('view');
});