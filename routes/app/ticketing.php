<?php

define_route()->prefix('ticketing')->as('app.ticketing.')->group(function() {
    define_route('listing', 'App\Ticketing\Listing')->name('listing');
    define_route('create', 'App\Ticketing\Create')->name('create');
    define_route('{ticketId}', 'App\Ticketing\Update')->name('update');
});
