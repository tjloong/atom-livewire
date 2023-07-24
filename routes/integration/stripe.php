<?php

// stripe
$route->get('__stripe/success', 'StripeController@success')->name('__stripe.success');
$route->get('__stripe/cancel', 'StripeController@cancel')->name('__stripe.cancel');
$route->get('__stripe/webhook', 'StripeController@webhook', 'post')->name('__stripe.webhook');