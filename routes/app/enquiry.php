<?php

// app/enquiry
$route->get('app/enquiry/{enquiryId?}',  'App\Enquiry')->middleware('auth')->name('app.enquiry');