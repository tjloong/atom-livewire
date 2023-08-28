<?php

// app/banner
$route->get('app/banner/{bannerId?}', 'App\Banner')->middleware('auth')->name('app.banner');