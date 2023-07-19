<?php

// app/setting
define_route('app/settings/{tab?}', 'App\Settings')->middleware('auth')->where('tab', '.*')->name('app.settings');
