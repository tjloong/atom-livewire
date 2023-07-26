<?php

// app/dashboard
$route->get('app/dashboard', 'App\Dashboard')->middleware('auth')->name('app.dashboard');
