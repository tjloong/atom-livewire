<?php

define_route()->prefix('contact')->as('app.contact.')->group(function() {
    // contact person
    define_route()->prefix('person')->as('person.')->group(function() {
        define_route('create/{contactId}', 'App\Contact\Person\Create')->name('create');
        define_route('{personId}', 'App\Contact\Person\View')->name('view');
        define_route('{personId}/update', 'App\Contact\Person\Update')->name('update');
    });

    define_route('listing/{category}', 'App\Contact\Listing')->name('listing');
    define_route('create/{category}', 'App\Contact\Create')->name('create');
    define_route('{contactId}/update', 'App\Contact\Update')->name('update');
    define_route('{contactId}/{tab?}', 'App\Contact\View')->name('view');
});
