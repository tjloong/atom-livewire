<?php

// app/onboarding
$route->get('app/onboarding', 'App\Onboarding')->name('app.onboarding');
$route->get('app/onboarding/completed', 'App\Onboarding\Completed')->name('app.onboarding.completed');
