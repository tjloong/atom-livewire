<?php

define_route()->prefix('label')->as('app.label.')->group(function() {
    define_route('create', 'App\Label\Create')->name('create');
    define_route('listing', 'App\Label\Listing')->name('listing');
    define_route('{labelId}', 'App\Label\Update')->name('update');
});
