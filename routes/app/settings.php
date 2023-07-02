<?php

define_route('settings/{tab?}', 'App\Settings\Index')
    ->name('settings')
    ->where('tab', '.*');
