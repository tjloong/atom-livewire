<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;

define_route()->middleware('auth')->group(function () {
    define_route('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return atom_view('auth.verify', ['action' => 'verified']);
    })->middleware('signed')->name('verification.verify');

    define_route('email/notify', function () {
        request()->user()->sendEmailVerificationNotification();
        return atom_view('auth.verify', ['action' => 'sent']);
    })->middleware('throttle:6,1')->name('verification.send');
});
