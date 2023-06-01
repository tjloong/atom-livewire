<?php

define_route()->prefix('banner')->as('app.banner.')->group(function() {
    define_route('listing', 'App\Banner\Listing')->name('listing');
    define_route('create', 'App\Banner\Create')->name('create');
    define_route('update/{bannerId}', 'App\Banner\Update')->name('update');
});
