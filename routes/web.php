<?php

// web
$route->get('blog/{slug?}', 'Web\Blog')->name('web.blog');
$route->get('contact-us/{slug?}', 'Web\ContactUs')->name('web.contact-us');
$route->get('{slug}', 'Web\Page')->where('slug', '.*')->name('web.page');
$route->get('/', 'Web\Home')->name('web.home');

// catchall
// $route->get('{slug}', 'Web\CatchAll')
//     // exclude slug
//     ->where(['slug' => '^(?!'.implode('|', [
//         'livewire',
//         'login',
//         'register',
//         'forgot-password',
//         'reset-password',
//         'email',
//         '__',
//     ]).').*$'])
//     ->name('web.catchall');
