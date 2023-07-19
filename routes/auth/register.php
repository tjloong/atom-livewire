<?php

// register
define_route('register', 'Auth\Register')->middleware('guest', 'track-ref')->name('register');
