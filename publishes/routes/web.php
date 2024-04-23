<?php

require_once atom_path('routes/system.php');
require_once atom_path('routes/integration/stripe.php');
require_once atom_path('routes/auth/login.php');
require_once atom_path('routes/auth/register.php');
require_once atom_path('routes/auth/socialite.php');

$route = app('route');

// app
$route->get('app/dashboard', 'App\Dashboard')->middleware('auth')->name('app.dashboard');
$route->get('app/blog/{blogId?}', 'App\Blog')->middleware('auth')->name('app.blog');
$route->get('app/enquiry/{enquiryId?}', 'App\Enquiry')->middleware('auth')->name('app.enquiry');
$route->get('app/banner/{bannerId?}', 'App\Banner')->middleware('auth')->name('app.banner');
$route->get('app/announcement/{announcementId?}', 'App\Announcement')->middleware('auth')->name('app.announcement');
$route->get('app/popup/{popupId?}', 'App\Popup')->middleware('auth')->name('app.popup');
$route->get('app/page/{pageId?}', 'App\Page')->middleware('auth')->name('app.page');
$route->get('app/signup/{signupId?}', 'App\Signup')->middleware('auth')->name('app.signup');
$route->get('app/notilog/{notilogId?}', 'App\Notilog')->middleware('auth')->name('app.notilog');
$route->get('app/share/{ulid}', 'App\Share')->name('app.share');
$route->get('app/settings/{tab?}', 'App\Settings')->middleware('auth')->where('tab', '.*')->name('app.settings');
$route->get('app/onboarding', 'App\Onboarding')->middleware('auth')->name('app.onboarding');
$route->get('app/onboarding/completed', 'App\Onboarding\Completed')->middleware('auth')->name('app.onboarding.completed');
$route->get('app', fn() => redirect(optional(user())->home() ?? '/'));

// web
$route->get('blog/{slug?}', 'Web\Blog')->name('web.blog');
$route->get('contact-us/{slug?}', 'Web\ContactUs')->name('web.contact-us');
$route->get('announcement/{slug}', 'Web\Announcement')->name('web.announcement');
$route->get('/', 'Web\Home')->name('web.home');
$route->get('{slug}', 'Web\Page')->where('slug', '.*')->name('web.page');