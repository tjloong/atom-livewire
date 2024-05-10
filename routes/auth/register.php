<?php

$route = app('route');

$route->get('register', 'Auth\Register')->middleware('guest')->name('auth.register');
