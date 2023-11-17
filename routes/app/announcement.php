<?php

// app/announcement
$route->get('app/announcement/{announcementId?}', 'App\Announcement')->middleware('auth')->name('app.announcement');