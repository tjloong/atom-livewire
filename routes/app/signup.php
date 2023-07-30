<?php

// app/signup
$route->get('app/signup/{signupId?}', 'App\Signup')->middleware('auth')->name('app.signup');