<?php

define_route()->prefix('role')->as('app.role.')->group(function() {
    define_route('create', 'App\Role\Create')->name('create');
    define_route('{roleId}', 'App\Role\Update')->name('update');
});
