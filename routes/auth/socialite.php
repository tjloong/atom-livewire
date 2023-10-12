<?php

$route = app('route');

$route->get('__auth/{provider}/redirect', 'SocialiteController@redirect')->name('socialite.redirect');
$route->get('__auth/{provider}/callback', 'SocialiteController@callback')->name('socialite.callback');
