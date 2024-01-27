<?php

// app/notilog
$route->get('app/notilog/{notilogId?}', 'App\Notilog')->middleware('auth')->name('app.notilog');