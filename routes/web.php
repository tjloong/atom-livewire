<?php

define_route('__sitemap', 'SitemapController@index')->name('__sitemap');
define_route('__pdf', 'PdfController@index')->name('__pdf');
define_route('__export/{filename}', 'ExportController@download')->name('__export');
define_route('__file/{id}', 'FileController@index')->name('__file');
define_route('__file/upload', 'FileController@upload', 'post')->name('__file.upload');
define_route('__file/download/{id}', 'FileController@download')->name('__file.download');
define_route('__locale/{locale}', 'LocaleController@set')->name('__locale.set');

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
            define_route()->prefix('blog')->as('app.blog.')->middleware('can:blog.manage')->group(function () {
                define_route('listing', 'App\Blog\Listing')->name('listing');
                define_route('create', 'App\Blog\Create')->name('create');
                define_route('{blog}', 'App\Blog\Update')->name('update');
            });
        }

        /**
         * Enquiries
         */
        if (enabled_module('enquiries')) {
            define_route()->prefix('enquiry')->as('app.enquiry.')->middleware('can:enquiry.manage')->group(function () {
                define_route('listing',  'App\Enquiry\Listing')->name('listing');
                define_route('{enquiry}', 'App\Enquiry\Update')->name('update');
            });
        }

        /**
         * Pages
         */
        if (enabled_module('pages')) {
            define_route()->prefix('page')->as('app.page.')->group(function () {
                define_route('listing',  'App\Page\Listing')->name('listing');
                define_route('{page}', 'App\Page\Update')->name('update');
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
         * Contacts
         */
        define_route()->prefix('contact')->as('app.contact.')->group(function() {
            define_route('listing/{type}', 'App\Contact\Listing')->middleware('can:contact.view')->name('listing');
            define_route('create/{type}', 'App\Contact\Create')->middleware('can:contact.create')->name('create');
            define_route('{contactId}', 'App\Contact\View')->middleware('can:contact.view')->name('view');
            define_route('{contactId}/update', 'App\Contact\Update')->middleware('can:contact.update')->name('update');
        });

        /**
         * Products
         */
        if (enabled_module('products')) {
            define_route()->prefix('product')->as('app.product.')->middleware('can:product.manage')->group(function() {
                define_route('listing', 'App\Product\Listing')->name('listing');
                define_route('create', 'App\Product\Create')->name('create');
                define_route('{productId}', 'App\Product\Update')->name('update');

                define_route()->prefix('{productId}/variant')->as('variant.')->group(function() {
                    define_route('create', 'App\Product\Variant\Create')->name('create');
                    define_route('{variantId}', 'App\Product\Variant\Update')->name('update');
                });
            });
        }

        /**
         * Promotions
         */
        if (enabled_module('promotions')) {
            define_route()->prefix('promotion')->as('app.promotion.')->middleware('can:promotion.manage')->group(function() {
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
         * Ticketing
         */
        if (enabled_module('ticketing')) {
            define_route()->prefix('ticketing')->as('app.ticketing.')->middleware('can:ticketing.manage')->group(function() {
                define_route('listing', 'App\Ticketing\Listing')->name('listing');
                define_route('create', 'App\Ticketing\Create')->name('create');
                define_route('{ticketId}', 'App\Ticketing\Update')->name('update');
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
         * Settings
         */
        define_route('settings/{tab?}', 'App\Settings\Index')
            ->name('app.settings')
            ->where('tab', '.*');

        /**
         * Preferences
         */
        define_route('preferences/{tab?}', 'App\Preferences\Index')
            ->middleware('can:preference.manage')
            ->name('app.preferences')
            ->where('tab', '.*');
    });
}

/**
 * Shareable
 */
if (enabled_module('shareables')) {
    define_route('shareable/{uuid}', 'Shareable')->name('shareable');
}

/**
 * Web
 */
if (enabled_module('blogs')) {
    define_route('blog/{slug?}', 'Web\Blog')->name('web.blog');
}

// A catch all route after the app is booted
// so this route will be register after the consuming app's routes
app()->booted(function() {
    define_route('{slug?}', 'Web\CatchAll')
        ->middleware('web')
        // slugs to exclude
        ->where(['slug' => '^(?!'.implode('|', [
            'livewire',
            'login',
            'register',
            'forgot-password',
            'reset-password',
            'email',
            '__',
        ]).').*$'])
        ->name('web.catchall');
});
