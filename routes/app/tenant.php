<?php

define_route()->prefix('tenant')->as('tenant.')->group(function() {
    define_route('create', 'App\Tenant\Create')->name('create');
});
