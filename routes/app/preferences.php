<?php

define_route('preferences/{tab?}', 'App\Preferences\Index')
    ->name('app.preferences')
    ->where('tab', '.*');
