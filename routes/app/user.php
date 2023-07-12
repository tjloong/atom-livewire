<?php

define_route()->prefix('user')->as('user.')->group(function() {
    define_route('create', 'App\User\Create')->name('create');
    define_route('{id}', 'App\User\Update')->name('update');
});
