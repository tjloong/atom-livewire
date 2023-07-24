<?php

// register
$route->get('register', 'Auth\Register')->middleware('guest', 'track-ref')->name('register');
