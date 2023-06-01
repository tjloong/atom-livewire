<?php

define_route()->prefix('invitation')->as('app.invitation.')->group(function() {
    define_route('create', 'App\Invitation\Create')->name('create');
    define_route('pending', 'App\Invitation\Pending')->name('pending');
    define_route('{invitationId}', 'App\Invitation\Update')->name('update');
});
