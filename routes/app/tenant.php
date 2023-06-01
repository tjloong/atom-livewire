<?php

define_route()->prefix('tenant')->as('app.tenant.')->group(function() {
    define_route('create', 'App\Tenant\Create')->name('create');
});
