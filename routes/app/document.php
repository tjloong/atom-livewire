<?php

define_route()->prefix('document')->as('app.document.')->group(function() {
    define_route('listing/{type}', 'App\Document\Listing')->name('listing');
    define_route('create/{type}', 'App\Document\Create')->name('create');

    define_route()->prefix('payment')->as('payment.')->group(function() {
        define_route('create/{documentId}', 'App\Document\Payment\Create')->name('create');
        define_route('{paymentId}', 'App\Document\Payment\View')->name('view');
        define_route('{paymentId}/update', 'App\Document\Payment\Update')->name('update');
    });

    define_route()->prefix('{documentId}')->group(function() {
        define_route('/', 'App\Document\View')->name('view');
        define_route('update', 'App\Document\Update')->name('update');
        define_route('split', 'App\Document\Split')->name('split');
    });
});
