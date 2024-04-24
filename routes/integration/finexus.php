<?php

$route = app('route');

$route->prefix('__finexus')->as('__finexus.')->withoutMiddleware('web')->group(function() use ($route) {
    $route->get('success', 'FinexusController@success')->name('success');
    $route->get('failed', 'FinexusController@failed')->name('failed');
    $route->get('cancel', 'FinexusController@cancel')->name('cancel');
    $route->get('query', 'FinexusController@query')->name('query');
});