<?php

define_route()->prefix('team')->as('team.')->group(function() {
    define_route('create', 'App\Team\Create')->name('create');
    define_route('{teamId}', 'App\Team\Update')->name('update');
});
