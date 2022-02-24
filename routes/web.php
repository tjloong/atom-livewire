<?php

use Jiannius\Atom\Models\Page;
use Illuminate\Support\Facades\Route;

if (!config('atom.static_site')) {
    define_route('__sitemap', 'SitemapController@index')->name('__sitemap');
    define_route('__export/{filename}', 'ExportController@download')->name('__export');

    Route::prefix('app')->middleware('auth')->group(function() {
        define_route('/', fn() => redirect()->route('dashboard'))->name('app.home');

        /**
         * Dashboard
         */
        define_route('dashboard', 'App\\Dashboard')->name('dashboard');

        /**
         * Blogs
         */
        if (enabled_feature('blogs')) {
            Route::prefix('blog')->group(function () {
                define_route('listing', 'App\\Blog\\Listing')->name('blog.listing');
                define_route('create', 'App\\Blog\\Create')->name('blog.create');
                define_route('{blog}/{tab?}', 'App\\Blog\\Update')->name('blog.update');
            });
        }

        /**
         * Enquiries
         */
        if (enabled_feature('enquiries')) {
            Route::prefix('enquiry')->group(function () {
                define_route('listing',  'App\\Enquiry\\Listing')->name('enquiry.listing');
                define_route('{enquiry}', 'App\\Enquiry\\Update')->name('enquiry.update');
            });
        }

        /**
         * Tickets
         */
        if (enabled_feature('tickets')) {
            Route::prefix('ticket')->group(function() {
                define_route('listing', 'App\\Ticket\\Listing')->name('ticket.listing');
                define_route('create', 'App\\Ticket\\Create')->name('ticket.create');
                define_route('{ticket}', 'App\\Ticket\\Update')->name('ticket.update');
            });
        }

        /**
         * Pages
         */
        if (enabled_feature('pages')) {
            Route::prefix('page')->group(function () {
                define_route('listing',  'App\\Page\\Listing')->name('page.listing');
                define_route('{id}', 'App\\Page\\Update')->name('page.update');
            });
        }
    
        /**
         * Teams
         */
        if (enabled_feature('teams')) {
            Route::prefix('team')->group(function () {
                define_route('listing', 'App\\Team\\Listing')->name('team.listing');
                define_route('create', 'App\\Team\\Create')->name('team.create');
                define_route('{team}', 'App\\Team\\Update')->name('team.update');
            });
        }
    
        /**
         * Label
         */
        if (enabled_feature('labels')) {
            Route::prefix('label')->group(function () {
                define_route('listing/{type?}', 'App\\Label\\Listing')->name('label.listing');
                define_route('create/{type}', 'App\\Label\\Create')->name('label.create');
                define_route('{id}', 'App\\Label\\Update')->name('label.update');
            });
        }
    
        /**
         * Site Settings
         */
        if (enabled_feature('site_settings')) {
            define_route('site-settings/{tab?}', 'App\SiteSettings\Index')->name('site-settings');
        }

        /**
         * Roles
         */
        Route::prefix('role')->group(function () {
            define_route('listing', 'App\\Role\\Listing')->name('role.listing');
            define_route('create', 'App\\Role\\Create')->name('role.create');
            define_route('{role}', 'App\\Role\\Update')->name('role.update');
        });

        /**
         * Users
         */
        Route::prefix('user')->group(function () {
            define_route('account', 'App\\User\\Account')->name('user.account');
            define_route('listing', 'App\\User\\Listing')->name('user.listing');
            define_route('create', 'App\\User\\Create')->name('user.create');
            define_route('{user}', 'App\\User\\Update')->name('user.update');
        });
    
        /**
         * Files
         */
        define_route('files', 'App\\File\\Listing')->name('files');
    });
}

if (enabled_feature('blogs')) {
    define_route('blogs/{slug?}', 'Web\\Blog')->name('blogs');
}

define_route('contact', 'Web\\Contact')->name('contact');
define_route('contact/thank-you', 'Web\\ContactSent')->name('contact.sent');

if (enabled_feature('pages') && !app()->runningInConsole()) {
    $slugs = Page::getSlugs();
    define_route('{slug}', 'Web\Page')->name('page')->where(['slug' => '(' . implode('|', $slugs) . ')']);
}

define_route('/', 'Web\\Home')->name('home');