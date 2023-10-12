<?php

$route = app('route');

$route->get('__revenue-monster/redirect', 'RevenueMonsterController@redirect')
    ->name('__revenue-monster.redirect')
    ->withoutMiddleware('web');

$route->post('__revenue-monster/webhook', 'RevenueMonsterController@webhook')
    ->name('__revenue-monster.webhook')
    ->withoutMiddleware('web');