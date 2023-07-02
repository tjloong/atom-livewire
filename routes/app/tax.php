<?php

define_route()->prefix('tax')->as('tax.')->group(function() {
    define_route('create', 'App\Tax\Create')->name('create');
    define_route('{taxId}', 'App\Tax\Update')->name('update');
});
