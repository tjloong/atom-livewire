<?php

// login
$route->get('login', 'Auth\Login')->middleware('guest')->name('login');
$route->get('logout', 'Auth\Logout')->middleware('auth')->name('logout');
$route->get('forgot-password', 'Auth\ForgotPassword')->middleware('guest')->name('password.forgot');
$route->get('reset-password', 'Auth\ResetPassword')->name('password.reset');
