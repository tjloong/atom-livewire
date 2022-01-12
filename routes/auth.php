<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Auth\ForgotPassword;

if (config('atom.features.auth.login') && class_exists(Login::class)) {
    Route::get('login', Login::class)->name('login');
}

if (config('atom.features.auth.register') && class_exists(Register::class)) {
    Route::get('register/{slug?}', Register::class)->name('register');
}

if (config('atom.features.auth.forgot-password')) {
    if (class_exists(ForgotPassword::class)) {
        Route::get('forgot-password', ForgotPassword::class)->middleware('guest')->name('password.forgot');
    }

    if (class_exists(ResetPassword::class)) {
        Route::get('reset-password', ResetPassword::class)->name('password.reset');
    }
}

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

