<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;

if (!config('atom.static_site')) {
    define_route('__auth/{provider}/redirect', 'SocialiteController@redirect')->name('socialite.redirect');
    define_route('__auth/{provider}/callback', 'SocialiteController@callback')->name('socialite.callback');

    define_route('login', 'Auth\Login')->name('login');
    define_route('forgot-password', 'Auth\ForgotPassword')->middleware('guest')->name('password.forgot');
    define_route('reset-password', 'Auth\ResetPassword')->name('password.reset');

    if (config('atom.auth.register')) {
        define_route('register', 'Auth\Register')->middleware('guest', 'track-ref')->name('register');
    }

    if (config('atom.auth.verify')) {
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
    }
}
