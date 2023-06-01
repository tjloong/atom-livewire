<?php

define_route()->prefix('signup')->as('app.signup.')->group(function() {
    define_route('listing', 'App\Signup\Listing')->name('listing');
    define_route('{userId}/{tab?}', 'App\Signup\Update')->name('update');
});
