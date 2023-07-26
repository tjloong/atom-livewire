<?php

// register
$route->get('register', 'Auth\Register')->middleware('guest')->name('register');
