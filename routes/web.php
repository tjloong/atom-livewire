<?php

use Jiannius\Atom\Models\Page;
use Illuminate\Support\Facades\Route;

if (!config('atom.static_site')) {
    define_route('__sitemap', 'SitemapController@index')->name('__sitemap');
    define_route('__export/{filename}', 'ExportController@download')->name('__export');

    /**
     * Account Portal
     */
    Route::prefix('account')->middleware('auth')->as('account.')->group(function() {
        define_route('/', fn() => redirect()->route('account.authentication'))->name('home');
        define_route('authentication', 'Account\\Authentication\\Index')->name('authentication');
    });

    /**
     * Onboarding Portal
     */
    if (enabled_module('accounts')) {
        Route::prefix('onboarding')->middleware('auth')->group(function() {
            define_route('/', 'Onboarding\\Index')->name('onboarding');
            define_route('completed', 'Onboarding\\Completed')->name('onboarding.completed');
        });
    }

    /**
     * Billing Portal
     */
    if (enabled_module('plans')) {
        Route::prefix('billing')->middleware(['auth', 'billing-portal-guard'])->group(function() {
            define_route('/', 'Billing\\Index')->name('billing');
            define_route('checkout', 'Billing\\Checkout')->name('billing.checkout');
        });
    }

    /**
     * Ticketing Portal
     */
    if (enabled_module('ticketing')) {
        Route::prefix('ticketing')->as('ticketing.')->group(function() {
            define_route('listing', 'Ticketing\\Listing')->name('listing');
            define_route('create', 'Ticketing\\Create')->name('create');
            define_route('{id}', 'Ticketing\\Update')->name('update');
        });
    }

    /**
     * App Portal
     */
    Route::prefix('app')->middleware(['auth', 'app-portal-guard'])->group(function() {
        define_route('/', fn() => redirect()->route('app.dashboard'))->name('app.home');

        /**
         * Dashboard
         */
        define_route('dashboard', 'App\\Dashboard')->name('app.dashboard');

        /**
         * Blogs
         */
        if (enabled_module('blogs')) {
            Route::prefix('blog')->as('app.blog.')->group(function () {
                define_route('listing', 'App\\Blog\\Listing')->name('listing');
                define_route('create', 'App\\Blog\\Create')->name('create');
                define_route('{id}/{tab?}', 'App\\Blog\\Update\\Index')->name('update');
            });
        }

        /**
         * Enquiries
         */
        if (enabled_module('enquiries')) {
            Route::prefix('enquiry')->as('app.enquiry.')->group(function () {
                define_route('listing',  'App\\Enquiry\\Listing')->name('listing');
                define_route('{id}', 'App\\Enquiry\\Update')->name('update');
            });
        }

        /**
         * Pages
         */
        if (enabled_module('pages')) {
            Route::prefix('page')->as('app.page.')->group(function () {
                define_route('listing',  'App\\Page\\Listing')->name('listing');
                define_route('{id}', 'App\\Page\\Update\\Index')->name('update');
            });
        }

        /**
         * Plans
         */
        if (enabled_module('plans')) {
            Route::prefix('plan')->as('app.plan.')->group(function() {
                define_route('listing', 'App\\Plan\\Listing')->name('listing');
                define_route('create', 'App\\Plan\\Create')->name('create');
                define_route('{id}', 'App\\Plan\\Update')->name('update');
            });

            Route::prefix('plan-price')->as('app.plan-price.')->group(function() {
                define_route('create/{id}', 'App\\PlanPrice\\Create')->name('create');
                define_route('{id}', 'App\\PlanPrice\\Update')->name('update');
            });
        }

        /**
         * Accounts
         */
        if (enabled_module('accounts')) {
            Route::prefix('account')->as('app.account.')->group(function() {
                define_route('listing', 'App\\Account\\Listing')->name('listing');
                define_route('{id}/{tab?}', 'App\\Account\\Update\\Index')->name('update');
            });
        }
    
        /**
         * Teams
         */
        if (enabled_module('teams')) {
            Route::prefix('team')->as('app.team.')->group(function () {
                define_route('listing', 'App\\Team\\Listing')->name('listing');
                define_route('create', 'App\\Team\\Create')->name('create');
                define_route('{id}', 'App\\Team\\Update')->name('update');
            });
        }
        
        /**
         * Roles
         */
        if (enabled_module('roles')) {
            Route::prefix('role')->as('app.role.')->group(function () {
                define_route('listing', 'App\\Role\\Listing')->name('listing');
                define_route('create', 'App\\Role\\Create')->name('create');
                define_route('{id}', 'App\\Role\\Update')->name('update');
            });
        }
    
        /**
         * Label
         */
        Route::prefix('label')->as('app.label.')->group(function () {
            define_route('listing/{type?}', 'App\\Label\\Listing')->name('listing');
            define_route('create/{type}', 'App\\Label\\Create')->name('create');
            define_route('{id}', 'App\\Label\\Update')->name('update');
        });
        
        /**
         * Users
         */
        Route::prefix('user')->as('app.user.')->group(function () {
            define_route('listing', 'App\\User\\Listing')->name('listing');
            define_route('create', 'App\\User\\Create')->name('create');
            define_route('{id}', 'App\\User\\Update')->name('update');
        });
        
        /**
         * Files
         */
        define_route('files', 'App\\File\\Listing')->name('app.files');
    
        /**
         * Site Settings
         */
        define_route('site-settings/{tab?}', 'App\SiteSettings\Index')->name('app.site-settings');
    });
}

if (enabled_module('blogs')) {
    define_route('blogs/{slug?}', 'Web\\Blog')->name('blogs');
}

define_route('contact', 'Web\\Contact')->name('contact');
define_route('contact/thank-you', 'Web\\ContactSent')->name('contact.sent');

if (enabled_module('pages') && !app()->runningInConsole()) {
    $slugs = Page::getSlugs();
    define_route('{slug}', 'Web\Page')->name('page')->where(['slug' => '(' . implode('|', $slugs) . ')']);
}

define_route('/', 'Web\\Home')->name('home');