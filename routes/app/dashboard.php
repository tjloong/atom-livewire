<?php

// app/dashboard
define_route('app/dashboard', 'App\Dashboard')->middleware('auth')->name('app.dashboard');
