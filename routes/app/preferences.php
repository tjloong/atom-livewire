<?php

define_route('preferences/{tab?}', 'App\Preferences\Index')
    ->name('preferences')
    ->where('tab', '.*');
