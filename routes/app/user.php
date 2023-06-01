<?php

define_route()->prefix('user')->as('app.user.')->group(function() {
    define_route('create', 'App\User\Create')->name('create');
    define_route('{userId}', 'App\User\Update')->name('update');
});
