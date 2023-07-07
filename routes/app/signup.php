<?php

define_route()->prefix('signup')->as('signup.')->group(function() {
    define_route('listing', 'App\Signup\Listing')->name('listing');
    define_route('{id}/{tab?}', 'App\Signup\Update')->name('update');
});
