<?php

define_route('settings/{tab?}', 'App\Settings\Index')
    ->name('app.settings')
    ->where('tab', '.*');
