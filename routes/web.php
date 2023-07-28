<?php

// web
$route->get('blog/{slug?}', 'Web\Blog')->name('web.blog');
$route->get('contact-us', 'Web\ContactUs')->name('web.contact-us');
$route->get('thank/enquiry', 'Web\Thank\Enquiry')->name('web.thank.enquiry');
$route->get('/', 'Web\Home')->name('web.home');

// define_route()->prefix('shop')->as('web.shop.')->group(function() {
//     define_route('/', 'Web\Shop\Listing')->name('listing');
//     define_route('cart', 'Web\Shop\Cart')->name('cart');
//     define_route('checkout', 'Web\Shop\Checkout')->name('checkout');
//     define_route('{slug}', 'Web\Shop\Product')->name('product');
// });

// catchall
$route->get('{slug}', 'Web\CatchAll')
    // exclude slug
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
