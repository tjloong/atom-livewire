<?php

// app/popup
$route->get('app/popup/{popupId?}', 'App\Popup')->middleware('auth')->name('app.popup');