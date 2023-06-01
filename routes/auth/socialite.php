<?php

define_route('__auth/{provider}/redirect', 'SocialiteController@redirect')->name('socialite.redirect');
define_route('__auth/{provider}/callback', 'SocialiteController@callback')->name('socialite.callback');
