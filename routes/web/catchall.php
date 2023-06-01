<?php

define_route('{slug}', 'Web\CatchAll')
    // exclude slug
    ->where(['slug' => '^(?!'.implode('|', [
        'livewire',
        'login',
        'register',
        'forgot-password',
        'reset-password',
        'email',
        '__',
    ]).').*$'])
    ->name('web.catchall');
