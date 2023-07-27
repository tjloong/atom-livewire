<?php

// app/enquiry
$route->get('app/enquiry',  'App\Enquiry')->middleware('auth')->name('app.enquiry');