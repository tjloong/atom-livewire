<?php

define_route()->prefix('billing')->group(function() {
    define_route('/', 'App\Billing')->name('app.billing');
    define_route('checkout', 'App\Billing\Checkout')->name('app.billing.checkout');
});
