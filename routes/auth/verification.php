<?php

// email verification
define_route('email/verify/{id}/{hash}', 'Auth\Verification')->middleware('auth', 'signed')->name('verification.verify');
define_route('email/notify', 'Auth\SendVerification')->middleware('auth', 'throttle:6,1')->name('verification.send');