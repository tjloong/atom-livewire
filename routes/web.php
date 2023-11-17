<?php

// web
$route->get('blog/{slug?}', 'Web\Blog')->name('web.blog');
$route->get('contact-us/{slug?}', 'Web\ContactUs')->name('web.contact-us');
$route->get('announcement/{slug}', 'Web\Announcement')->name('web.announcement');
$route->get('/', 'Web\Home')->name('web.home');
$route->get('{slug}', 'Web\Page')->where('slug', '.*')->name('web.page');