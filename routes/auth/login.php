<?php

$route = app('route');

$route->as('auth.')->group(function() use ($route) {
    $route->get('login', 'Auth\Login')->name('login');
    $route->get('logout', 'Auth\Logout')->middleware('auth')->name('logout');
    $route->get('forgot-password', 'Auth\ForgotPassword')->middleware('guest')->name('password.forgot');
    $route->get('reset-password', 'Auth\ResetPassword')->name('password.reset');
});
