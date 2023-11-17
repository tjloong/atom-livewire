<?php

// app/onboarding
$route->get('app/onboarding/completed', 'App\Onboarding\Completed')->name('app.onboarding.completed');
$route->get('app/onboarding/{tab?}', 'App\Onboarding')->name('app.onboarding');
