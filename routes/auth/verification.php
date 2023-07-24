<?php

// email verification
$route->get('email/verify/{id}/{hash}', 'Auth\Verification')->middleware('auth', 'signed')->name('verification.verify');
$route->get('email/notify', 'Auth\SendVerification')->middleware('auth', 'throttle:6,1')->name('verification.send');