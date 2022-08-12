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
        define_route('checkout', 'StripeController@checkout')->name('checkout');
        define_route('success', 'StripeController@success')->name('success');
        define_route('cancel', 'StripeController@cancel')->name('cancel');
        define_route('webhook', 'StripeController@webhook', 'post')->name('webhook');
        define_route('cancel-subscription', 'StripeController@cancelSubscription')->name('cancel-subscription');
    });
}

/**
 * Ozopay
 */
if (in_array('ozopay', config('atom.payment_gateway'))) {
    define_route()->prefix('__ozopay')->as('__ozopay.')->group(function() {
        define_route('checkout', 'OzopayController@checkout')->name('checkout');
        define_route('redirect', 'OzopayController@redirect', 'post')->name('redirect');
        define_route('webhook', 'OzopayController@webhook', 'post')->name('webhook');
    });
}

/**
 * iPay88
 */
if (in_array('ipay', config('atom.payment_gateway'))) {
    define_route()->prefix('__ipay')->as('__ipay.')->group(function() {
        define_route('checkout', 'IpayController@checkout')->name('checkout');
        define_route('redirect', 'IpayController@redirect', 'post')->name('redirect');
        define_route('webhook', 'IpayController@webhook', 'post')->name('webhook');
    });
}

/**
 * Gkash
 */
if (in_array('gkash', config('atom.payment_gateway'))) {
    define_route()->prefix('__gkash')->as('__gkash.')->group(function() {
        define_route('checkout', 'GkashController@checkout')->name('checkout');
        define_route('redirect', 'GkashController@redirect', 'post')->name('redirect');
        define_route('webhook', 'GkashController@webhook', 'post')->name('webhook');
    });
}

/**
 * Main
 */
if (!config('atom.static_site')) {
    define_route()->prefix('app')->middleware('auth')->group(function() {
        define_route('/', fn() => redirect()->route('app.dashboard'))->name('app.home');

        /**
         * Dashboard
         */
        define_route('dashboard', 'App\Dashboard')->name('app.dashboard');

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
        define_route()->prefix('account')->as('app.account.')->group(function() {
            define_route('/', 'App\Account\Update\Index')->name('home');

            if (config('atom.accounts.register')) {
                define_route('listing', 'App\Account\Listing')->name('listing');
                define_route('{account}', 'App\Account\Update\Index')->name('update');
            }
        });

        /**
         * Onboarding
         */
        if (config('atom.accounts.register')) {
            define_route()->prefix('onboarding')->as('app.onboarding.')->group(function() {
                define_route('/', 'App\Onboarding\Index')->name('home');
                define_route('completed', 'App\Onboarding\Completed')->name('completed');
            });
        }

        /**
         * Products
         */
        if (enabled_module('products')) {
            define_route()->prefix('product')->as('app.product.')->group(function() {
                define_route('listing', 'App\Product\Listing')->name('listing');
                define_route('create', 'App\Product\Create')->name('create');
                define_route('{product}', 'App\Product\Update\Index')->name('update');
            });

            define_route()->prefix('product-variant')->as('app.product-variant.')->group(function() {
                define_route('create', 'App\ProductVariant\Create')->name('create');
                define_route('{productVariant}', 'App\ProductVariant\Update')->name('update');
            });
        }

        /**
         * Promotions
         */
        if (enabled_module('promotions')) {
            define_route()->prefix('promotion')->as('app.promotion.')->group(function() {
                define_route('listing', 'App\Promotion\Listing')->name('listing');
                define_route('create', 'App\Promotion\Create')->name('create');
                define_route('{promotion}', 'App\Promotion\Update')->name('update');
            });
        }

        /**
         * Plans
         */
        if (enabled_module('plans')) {
            define_route()->prefix('plan')->as('app.plan.')->group(function() {
                define_route('listing', 'App\Plan\Listing')->name('listing');
                define_route('create', 'App\Plan\Create')->name('create');
                define_route('{plan}', 'App\Plan\Update\Index')->name('update');
            });

            define_route()->prefix('plan-price')->as('app.plan-price.')->group(function() {
                define_route('create/{plan}', 'App\PlanPrice\Create')->name('create');
                define_route('{planPrice}', 'App\PlanPrice\Update')->name('update');
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
            define_route('{label}', 'App\Label\Update\Index')->name('update');
        });
        
        /**
         * Users
         */
        define_route()->prefix('user')->as('app.user.')->group(function () {
            define_route('listing', 'App\User\Listing')->name('listing');
            define_route('create', 'App\User\Create')->name('create');
            define_route('{user}', 'App\User\Update')->name('update');
        });

        /**
         * Ticketing
         */
        if (enabled_module('ticketing')) {
            define_route()->prefix('ticketing')->as('app.ticketing.')->group(function() {
                define_route('listing', 'App\Ticketing\Listing')->name('listing');
                define_route('create', 'App\Ticketing\Create')->name('create');
                define_route('{ticket}', 'App\Ticketing\Update')->name('update');
            });
        }

        /**
         * Billing
         */
        if (enabled_module('plans')) {
            define_route()->prefix('billing')->as('app.billing.')->group(function() {
                define_route('/', 'App\Billing\Index')->name('home');
                define_route('plans', 'App\Billing\Plans')->name('plans');
                define_route('checkout', 'App\Billing\Checkout')->name('checkout');
            });    
        }
        
        /**
         * Files
         */
        define_route('files', 'App\File\Listing')->name('app.files');
    
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
    define_route('{slug?}', 'Web\Index')
        ->middleware('web', 'locale')
        ->name('page')
        // slugs to exclude
        ->where(['slug' => '^(?!'.implode('|', [
            'livewire',
            'login',
            'register',
            'forgot-password',
            'reset-password',
            'email',
            '__',
        ]).').*$']);
});
