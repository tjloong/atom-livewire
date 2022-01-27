<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

if (!config('atom.static_site')) {
    try {
        Route::get('login', 'App\\Http\\Livewire\\Auth\\Login')->name('login');
    } catch (Exception $e) {
        Route::get('login', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\Login')->name('login');
    }

    if (config('atom.features.auth.register')) {
        try {
            Route::get('register', 'App\\Http\\Livewire\\Auth\\Register')->name('register');
        } catch (Exception $e) {
            Route::get('register', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\Register')->name('register');
        }
    }
    
    if (config('atom.features.auth.forgot-password')) {
        try {
            Route::get('forgot-password', 'App\\Http\\Livewire\\Auth\\ForgotPassword')->middleware('guest')->name('password.forgot');
        } catch (Exception $e) {
            Route::get('forgot-password', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\ForgotPassword')->middleware('guest')->name('password.forgot');
        }

        try {
            Route::get('reset-password', 'App\\Http\\Livewire\\Auth\\ResetPassword')->name('password.reset');
        } catch (Exception $e) {
            Route::get('reset-password', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\ResetPassword')->name('password.reset');
        }
    }
    
    if (config('atom.features.auth.verify')) {
        Route::middleware('auth')->group(function () {
            Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
                $request->fulfill();
                return view('atom::auth.verify', ['action' => 'verified']);
            })->middleware('signed')->name('verification.verify');
        
            Route::get('email/notify', function () {
                request()->user()->sendEmailVerificationNotification();
                return view('atom::auth.verify', ['action' => 'sent']);
            })->middleware('throttle:6,1')->name('verification.send');
        });
    }
}
