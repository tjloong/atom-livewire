<?php

$route = app('route');

$route->default();
$route->auth(login: true, register: true, socialite: true);
$route->onboarding();

$route->app(function() use ($route) {
    $route->get('/', fn() => redirect(user()->home()) ?? '/');
    $route->get('dashboard', 'App\Dashboard')->name('.dashboard');
    $route->get('blog/{blogId?}', 'App\Blog')->name('.blog');
    $route->get('enquiry/{enquiryId?}', 'App\Enquiry')->name('.enquiry');
    $route->get('banner/{bannerId?}', 'App\Banner')->name('.banner');
    $route->get('announcement/{announcementId?}', 'App\Announcement')->name('.announcement');
    $route->get('popup/{popupId?}', 'App\Popup')->name('.popup');
    $route->get('page/{pageId?}', 'App\Page')->name('.page');
    $route->get('signup/{signupId?}', 'App\Signup')->name('.signup');
    $route->get('notilog/{notilogId?}', 'App\Notilog')->name('.notilog');
    $route->get('share/{ulid}', 'App\Share')->name('.share');
    $route->get('settings/{tab?}', 'App\Settings')->where('tab', '.*')->name('.settings');
});

$route->web(function() use ($route) {
    $route->get('blog/{slug?}', 'Web\Blog')->name('.blog');
    $route->get('contact-us/{slug?}', 'Web\ContactUs')->name('.contact-us');
    $route->get('announcement/{slug}', 'Web\Announcement')->name('.announcement');
    $route->get('/', 'Web\Home')->name('.home');
    $route->get('{slug}', 'Web\Page')->where('slug', '.*')->name('.page');
});
