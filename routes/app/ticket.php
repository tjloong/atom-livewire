<?php

define_route()->prefix('ticket')->as('ticket.')->group(function() {
    define_route('listing', 'App\Ticket\Listing')->name('listing');
    define_route('create', 'App\Ticket\Create')->name('create');
    define_route('{id}', 'App\Ticket\Update')->name('update');
});
