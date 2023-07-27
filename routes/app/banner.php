<?php

// app/banner
$route->get('app/banner', 'App\Banner')->middleware('auth')->name('app.banner');