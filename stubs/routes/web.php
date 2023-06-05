<?php

// system
load_routes('system');

// integration
load_routes('integration.gkash');
load_routes('integration.ipay');
load_routes('integration.ozopay');
load_routes('integration.stripe');
load_routes('integration.revmon');

// auth
load_routes('auth.login');
load_routes('auth.register');
load_routes('auth.socialite');
load_routes('auth.verify');

// app
define_route()->prefix('app')->middleware('auth')->group(function() {
    define_route()->redirect('/', '/app/dashboard');

    load_routes('app.banner');
    load_routes('app.billing');
    load_routes('app.blog');
    load_routes('app.contact');
    load_routes('app.coupon');
    load_routes('app.dashboard');
    load_routes('app.document');
    load_routes('app.enquiry');
    load_routes('app.invitation');
    load_routes('app.label');
    load_routes('app.onboarding');
    load_routes('app.order');
    load_routes('app.page');
    load_routes('app.payment');
    load_routes('app.plan');
    load_routes('app.product');
    load_routes('app.preferences');
    load_routes('app.role');
    load_routes('app.settings');
    load_routes('app.shipping');
    load_routes('app.signup');
    load_routes('app.tax');
    load_routes('app.team');
    load_routes('app.tenant');
    load_routes('app.ticketing');
    load_routes('app.user');
});

// web
load_routes('web.blog');
load_routes('web.contact');
load_routes('web.product');
load_routes('web.shareable');
load_routes('web.thank');

// default
load_routes('web.home');
load_routes('web.catchall');