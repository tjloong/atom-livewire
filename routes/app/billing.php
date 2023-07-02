<?php

define_route()->prefix('billing')->group(function() {
    define_route('/', 'App\Billing')->name('billing');
    define_route('checkout', 'App\Billing\Checkout')->name('billing.checkout');
});
