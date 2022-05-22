<?php

use Illuminate\Support\Facades\Route;

define_route('__sitemap', 'SitemapController@index')->name('__sitemap');
define_route('__pdf', 'PdfController@index')->name('__pdf');
define_route('__export/{filename}', 'ExportController@download')->name('__export');
define_route('__file/{id}', 'FileController@index')->name('__file');
define_route('__file/download/{id}', 'FileController@download')->name('__file.download');

/**
 * Stripe
 */
if (in_array('stripe', config('atom.payment_gateway'))) {
    define_route()->prefix('__stripe')->as('__stripe.')->group(function() {
        define_route('sign', 'StripeController@sign', 'post')->name('sign');
        define_route('success', 'StripeController@success')->name('success');
        define_route('cancel', 'StripeController@cancel')->name('cancel');
        define_route('webhook', 'StripeController@webhook', 'post')->name('webhook');
    });
}

/**
 * Ozopay
 */
if (in_array('ozopay', config('atom.payment_gateway'))) {
    define_route()->prefix('__ozopay')->as('__ozopay.')->group(function() {
        define_route('sign', 'OzopayController@sign', 'post')->name('sign');
        define_route('redirect', 'OzopayController@redirect', 'post')->name('redirect');
        define_route('webhook', 'OzopayController@webhook', 'post')->name('webhook');
    });
}

/**
 * iPay88
 */
if (in_array('ipay', config('atom.payment_gateway'))) {
    define_route()->prefix('__ipay')->as('__ipay.')->group(function() {
        define_route('sign', 'IpayController@sign', 'post')->name('sign');
        define_route('redirect', 'IpayController@redirect', 'post')->name('redirect');
        define_route('webhook', 'IpayController@webhook', 'post')->name('webhook');
    });
}

/**
 * Gkash
 */
if (in_array('gkash', config('atom.payment_gateway'))) {
    define_route()->prefix('__gkash')->as('__gkash.')->group(function() {
        define_route('sign', 'GkashController@sign', 'post')->name('sign');
        define_route('redirect', 'GkashController@redirect', 'post')->name('redirect');
        define_route('webhook', 'GkashController@webhook', 'post')->name('webhook');
    });
}

/**
 * Main
 */
if (!config('atom.static_site')) {
    /**
     * Account Portal
     */
    define_route('account', 'Account\Index')->middleware('auth', 'locale')->name('account');

    /**
     * Onboarding Portal
     */
    if (config('atom.accounts.register')) {
        Route::prefix('onboarding')->middleware('auth', 'portal-guard', 'locale')->group(function() {
            define_route('/', 'Onboarding\\Index')->name('onboarding');
            define_route('completed', 'Onboarding\\Completed')->name('onboarding.completed');
        });
    }

    /**
     * Billing Portal
     */
    if (enabled_module('plans')) {
        Route::prefix('billing')->middleware('auth', 'portal-guard', 'locale')->group(function() {
            define_route('/', 'Billing\Index')->name('billing');
            define_route('plans', 'Billing\Plans')->name('billing.plans');
            define_route('checkout', 'Billing\Checkout')->name('billing.checkout');

            define_route()->prefix('account-payment')->as('billing.account-payment.')->group(function() {
                define_route('listing', 'App\AccountPayment\Listing')->name('listing');
                define_route('{accountPayment}', 'App\AccountPayment\Update')->name('update');
            });
        });
    }

    /**
     * Ticketing Portal
     */
    if (enabled_module('ticketing')) {
        Route::prefix('ticketing')->middleware('auth', 'portal-guard', 'locale')->as('ticketing.')->group(function() {
            define_route('listing', 'Ticketing\\Listing')->name('listing');
            define_route('create', 'Ticketing\\Create')->name('create');
            define_route('{id}', 'Ticketing\\Update')->name('update');
        });
    }

    /**
     * App Portal
     */
    Route::prefix('app')->middleware('auth', 'portal-guard', 'locale')->group(function() {
        define_route('/', fn() => redirect()->route('app.dashboard'))->name('app.home');

        /**
         * Dashboard
         */
        define_route('dashboard', 'App\\Dashboard')->name('app.dashboard');

        /**
         * Blogs
         */
        if (enabled_module('blogs')) {
            define_route()->prefix('blog')->as('app.blog.')->group(function () {
                define_route('listing', 'App\Blog\Listing')->name('listing');
                define_route('create', 'App\Blog\Create')->name('create');
                define_route('{blog}', 'App\Blog\Update\Index')->name('update');
            });
        }

        /**
         * Enquiries
         */
        if (enabled_module('enquiries')) {
            Route::prefix('enquiry')->as('app.enquiry.')->group(function () {
                define_route('listing',  'App\Enquiry\Listing')->name('listing');
                define_route('{enquiry}', 'App\Enquiry\Update')->name('update');
            });
        }

        /**
         * Pages
         */
        if (enabled_module('pages')) {
            Route::prefix('page')->as('app.page.')->group(function () {
                define_route('listing',  'App\\Page\\Listing')->name('listing');
                define_route('{page}', 'App\\Page\\Update\\Index')->name('update');
            });
        }

        /**
         * Accounts
         */
        if (config('atom.accounts.register')) {
            Route::prefix('account')->as('app.account.')->group(function() {
                define_route('listing', 'App\Account\Listing')->name('listing');
                define_route('{account}', 'App\Account\Update\Index')->name('update');
            });
        }

        /**
         * Plans
         */
        if (enabled_module('plans')) {
            define_route()->prefix('plan')->as('app.plan.')->group(function() {
                define_route('listing', 'App\Plan\Listing')->name('listing');
                define_route('create', 'App\Plan\Create')->name('create');
                define_route('{id}', 'App\Plan\Update')->name('update');
            });

            define_route()->prefix('plan-price')->as('app.plan-price.')->group(function() {
                define_route('create/{id}', 'App\PlanPrice\Create')->name('create');
                define_route('{id}', 'App\PlanPrice\Update')->name('update');
            });

            define_route()->prefix('account-payment')->as('app.account-payment.')->group(function() {
                define_route('listing', 'App\AccountPayment\Listing')->name('listing');
                define_route('{accountPayment}', 'App\AccountPayment\Update')->name('update');
            });
        }

        /**
         * Taxes
         */
        if (enabled_module('taxes')) {
            define_route()->prefix('tax')->as('app.tax.')->group(function() {
                define_route('listing', 'App\Tax\Listing')->name('listing');
                define_route('create', 'App\Tax\Create')->name('create');
                define_route('{tax}', 'App\Tax\Update')->name('update');
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
        define_route()->prefix('label')->as('app.label.')->group(function () {
            define_route('listing', 'App\Label\Listing')->name('listing');
            define_route('create/{type}', 'App\Label\Create')->name('create');
            define_route('{label}', 'App\Label\Update')->name('update');
        });
        
        /**
         * Users
         */
        Route::prefix('user')->as('app.user.')->group(function () {
            define_route('listing', 'App\\User\\Listing')->name('listing');
            define_route('create', 'App\\User\\Create')->name('create');
            define_route('{user}', 'App\\User\\Update')->name('update');
        });
        
        /**
         * Files
         */
        define_route('files', 'App\\File\\Listing')->name('app.files');
    
        /**
         * Site Settings
         */
        define_route('site-settings', 'App\SiteSettings\Index')->name('app.site-settings');
    });
}

/**
 * Web
 */
// A catch all route after the app is booted
// so this route will be register after the consuming app's routes
app()->booted(function() {
    define_route('{slug?}', 'Web\\Index')
        ->middleware('web', 'locale')
        ->name('page')
        // slugs to exclude
        ->where(['slug' => '^(?!'.implode('|', [
            'livewire',
            'account',
            'onboarding',
            'billing',
            'ticketing',
            'login',
            'register',
            'forgot-password',
            'reset-password',
            'email',
            '__',
        ]).').*$']);
});
