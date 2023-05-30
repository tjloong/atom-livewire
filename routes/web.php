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
        define_route('/', fn() => user() 
            ? redirect(user()->home())
            : redirect()->route('app.dashboard')
        )->name('app.home');

        /**
         * Dashboard
         */
        define_route('dashboard', 'App\Dashboard')->name('app.dashboard');

        /**
         * Users
         */
        define_route()->prefix('user')->as('app.user.')->group(function() {
            define_route('create', 'App\User\Create')->name('create');
            define_route('{userId}', 'App\User\Update')->name('update');
        });

        /**
         * Labels
         */
        define_route()->prefix('label')->as('app.label.')->group(function() {
            define_route('create', 'App\Label\Create')->name('create');
            define_route('listing', 'App\Label\Listing')->name('listing');
            define_route('{labelId}', 'App\Label\Update')->name('update');
        });

        if (config('atom.auth.register')) {
            /**
             * Sign-Ups
             */
            define_route()->prefix('signup')->as('app.signup.')->group(function() {
                define_route('listing', 'App\Signup\Listing')->name('listing');
                define_route('{userId}/{tab?}', 'App\Signup\Update')->name('update');
            });

            /**
             * Onboarding
             */
            define_route('onboarding/{tab?}', 'App\Onboarding')->name('app.onboarding');
        }

        /**
         * Tenant
         */
        if (enabled_module('tenants')) {
            define_route()->prefix('tenant')->as('app.tenant.')->group(function() {
                define_route('create', 'App\Tenant\Create')->name('create');
            });
        }

        /**
         * Invitation
         */
        if (enabled_module('invitations')) {
            define_route()->prefix('invitation')->as('app.invitation.')->group(function() {
                define_route('create', 'App\Invitation\Create')->name('create');
                define_route('pending', 'App\Invitation\Pending')->name('pending');
                define_route('{invitationId}', 'App\Invitation\Update')->name('update');
            });
        }

        /**
         * Role
         */
        if (enabled_module('roles')) {
            define_route()->prefix('role')->as('app.role.')->group(function() {
                define_route('create', 'App\Role\Create')->name('create');
                define_route('{roleId}', 'App\Role\Update')->name('update');
            });
        }

        /**
         * Team
         */
        if (enabled_module('teams')) {
            define_route()->prefix('team')->as('app.team.')->group(function() {
                define_route('create', 'App\Team\Create')->name('create');
                define_route('{teamId}', 'App\Team\Update')->name('update');
            });
        }

        /**
         * Blogs
         */
        if (enabled_module('blogs')) {
            define_route()->prefix('blog')->as('app.blog.')->group(function () {
                define_route('listing', 'App\Blog\Listing')->name('listing');
                define_route('create', 'App\Blog\Create')->name('create');
                define_route('{blogId}', 'App\Blog\Update')->name('update');
            });
        }

        /**
         * Enquiries
         */
        if (enabled_module('enquiries')) {
            define_route()->prefix('enquiry')->as('app.enquiry.')->group(function () {
                define_route('listing',  'App\Enquiry\Listing')->name('listing');
                define_route('{enquiryId}', 'App\Enquiry\Update')->name('update');
            });
        }

        /**
         * Pages
         */
        if (enabled_module('pages')) {
            define_route()->prefix('page')->as('app.page.')->group(function () {
                define_route('listing',  'App\Page\Listing')->name('listing');
                define_route('{pageId}', 'App\Page\Update')->name('update');
            });
        }

        /**
         * Taxes
         */
        if (enabled_module('taxes')) {
            define_route()->prefix('tax')->as('app.tax.')->group(function() {
                define_route('create', 'App\Tax\Create')->name('create');
                define_route('{taxId}', 'App\Tax\Update')->name('update');
            });
        }

        /**
         * Banners
         */
        if (enabled_module('banners')) {
            define_route()->prefix('banner')->as('app.banner.')->group(function() {
                define_route('listing', 'App\Banner\Listing')->name('listing');
                define_route('create', 'App\Banner\Create')->name('create');
                define_route('update/{bannerId}', 'App\Banner\Update')->name('update');
            });
        }

        /**
         * Contacts
         */
        if (enabled_module('contacts')) {
            define_route()->prefix('contact')->as('app.contact.')->group(function() {
                // contact person
                define_route()->prefix('person')->as('person.')->group(function() {
                    define_route('create/{contactId}', 'App\Contact\Person\Create')->name('create');
                    define_route('{personId}', 'App\Contact\Person\View')->name('view');
                    define_route('{personId}/update', 'App\Contact\Person\Update')->name('update');
                });

                define_route('listing/{category}', 'App\Contact\Listing')->name('listing');
                define_route('create/{category}', 'App\Contact\Create')->name('create');
                define_route('{contactId}/update', 'App\Contact\Update')->name('update');
                define_route('{contactId}/{tab?}', 'App\Contact\View')->name('view');
            });
        }

        /**
         * Products
         */
        if (enabled_module('products')) {
            define_route()->prefix('product')->as('app.product.')->group(function() {
                define_route('listing', 'App\Product\Listing')->name('listing');
                define_route('create', 'App\Product\Create')->name('create');
                define_route('{productId}/{tab?}', 'App\Product\Update')->name('update')->whereNumber('productId');
                
                // product variant
                define_route()->prefix('variant')->as('variant.')->group(function() {
                    define_route('create/{productId}', 'App\Product\Variant\Create')->name('create');
                    define_route('{variantId}', 'App\Product\Variant\Update')->name('update');
                });
            });
        }

        /**
         * Coupons
         */
        if (enabled_module('coupons')) {
            define_route()->prefix('coupon')->as('app.coupon.')->group(function() {
                define_route('listing', 'App\Coupon\Listing')->name('listing');
                define_route('create', 'App\Coupon\Create')->name('create');
                define_route('{couponId}', 'App\Coupon\Update')->name('update');
            });
        }

        /**
         * Orders
         */
        if (enabled_module('orders')) {
            define_route()->prefix('order')->as('app.order.')->group(function() {
                define_route('listing', 'App\Order\Listing')->name('listing');
                define_route('create', 'App\Order\Create')->name('create');
                define_route('{orderId}', 'App\Order\Update')->name('update');
            });
        }

        /**
         * Shipments
         */
        if (enabled_module('shippings')) {
            define_route()->prefix('shipping')->as('app.shipping.')->group(function() {
                define_route('listing', 'App\Shipping\Listing')->name('listing');
                define_route('create', 'App\Shipping\Create')->name('create');
                define_route('{rateId}', 'App\Shipping\Update')->name('update');
            });
        }

        /**
         * Payments
         */
        if (enabled_module('payments')) {
            define_route()->prefix('payment')->as('app.payment.')->group(function() {
                define_route('listing', 'App\Payment\Listing')->name('listing');
                define_route('create', 'App\Payment\Create')->name('create');
                define_route('{paymentId}', 'App\Payment\Update')->name('update');
            });
        }

        /**
         * Documents
         */
        if (enabled_module('documents')) {
            define_route()->prefix('document')->as('app.document.')->group(function() {
                define_route('listing/{type}', 'App\Document\Listing')->name('listing');
                define_route('create/{type}', 'App\Document\Create')->name('create');

                define_route()->prefix('payment')->as('payment.')->group(function() {
                    define_route('create/{documentId}', 'App\Document\Payment\Create')->name('create');
                    define_route('{paymentId}', 'App\Document\Payment\View')->name('view');
                    define_route('{paymentId}/update', 'App\Document\Payment\Update')->name('update');
                });

                define_route()->prefix('{documentId}')->group(function() {
                    define_route('/', 'App\Document\View')->name('view');
                    define_route('update', 'App\Document\Update')->name('update');
                    define_route('split', 'App\Document\Split')->name('split');
                });
            });
        }

        /**
         * Ticketing
         */
        if (enabled_module('ticketing')) {
            define_route()->prefix('ticketing')->as('app.ticketing.')->group(function() {
                define_route('listing', 'App\Ticketing\Listing')->name('listing');
                define_route('create', 'App\Ticketing\Create')->name('create');
                define_route('{ticketId}', 'App\Ticketing\Update')->name('update');
            });
        }

        /**
         * Plans
         */
        if (enabled_module('plans')) {
            define_route()->prefix('plan')->as('app.plan.')->group(function() {
                define_route('listing', 'App\Plan\Listing')->name('listing');
                define_route('create', 'App\Plan\Create')->name('create');

                define_route()->prefix('subscription')->as('subscription.')->group(function() {
                    define_route('listing', 'App\Plan\Subscription\Listing')->name('listing');
                    define_route('create', 'App\Plan\Subscription\Create')->name('create');
                    define_route('{subscriptionId}', 'App\Plan\Subscription\Update')->name('update');
                });

                define_route()->prefix('payment')->as('payment.')->group(function() {
                    define_route('listing', 'App\Plan\Payment\Listing')->name('listing');
                    define_route('create', 'App\Plan\Payment\Create')->name('create');
                    define_route('{paymentId}', 'App\Plan\Payment\Update')->name('update');                    
                });

                define_route()->prefix('{planId}')->group(function() {
                    define_route()->prefix('price')->as('price.')->group(function() {
                        define_route('listing', 'App\Plan\Price\Listing')->name('listing');
                        define_route('create', 'App\Plan\Price\Create')->name('create');
                        define_route('{priceId}', 'App\Plan\Price\Update')->name('update');
                    });

                    define_route('{tab?}', 'App\Plan\Update')->name('update');
                });
            });

            define_route()->prefix('billing')->group(function() {
                define_route('/', 'App\Billing')->name('app.billing');
                define_route('checkout', 'App\Billing\Checkout')->name('app.billing.checkout');
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
            ->name('app.preferences')
            ->where('tab', '.*');
    });
}

/**
 * Shareable
 */
if (enabled_module('shareables')) {
    define_route('shareable/{id}', 'Shareable')->name('shareable');
}

/**
 * Blogs
 */
if (enabled_module('blogs')) {
    define_route('blog/{slug?}', 'Web\Blog')->name('web.blog');
}

/**
 * Thank you
 */
define_route('thank/{slug?}', 'Web\Thank')->name('web.thank');

/**
 * Landing page
 */
define_route('/', 'Web\Home')->name('web.home');

// A catch all route after the app is booted
// so this route will be register after the consuming app's routes
app()->booted(function() {
    define_route('{slug}', 'Web\CatchAll')
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
