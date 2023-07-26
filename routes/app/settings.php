<?php

// app/setting
$route->get('app/settings/{tab?}', 'App\Settings')->middleware('auth')->where('tab', '.*')->name('app.settings');
