<?php

define_route()->prefix('enquiry')->as('enquiry.')->group(function () {
    define_route('listing',  'App\Enquiry\Listing')->name('listing');
    define_route('{enquiryId}', 'App\Enquiry\Update')->name('update');
});
