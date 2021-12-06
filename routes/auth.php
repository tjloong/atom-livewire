<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

if (class_exists(App\Http\Livewire\Auth\Login::class)) {
    Route::get('login', App\Http\Livewire\Auth\Login::class)->name('login');
}

if (class_exists(App\Http\Livewire\Auth\Register::class)) {
    Route::get('register/{slug?}', App\Http\Livewire\Auth\Register::class)->name('register');
}

Route::middleware('guest')->group(function () {
    if (class_exists(App\Http\Livewire\Auth\ForgotPassword::class)) {
        Route::get('forgot-password', App\Http\Livewire\Auth\ForgotPassword::class)->name('password.forgot');
    }

    if (class_exists(App\Http\Livewire\Auth\ResetPassword::class)) {
        Route::get('reset-password', App\Http\Livewire\Auth\ResetPassword::class)->name('password.reset');
    }
});

Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        session()->flash('flash', 'Your email address is verified::success');
        
        return redirect(RouteServiceProvider::HOME);
    })
    ->middleware('signed')
    ->name('verification.verify');

    Route::post('email/notify', function () {
        request()->user()->sendEmailVerificationNotification();

        session()->flash('flash', 'A new verification link has been sent to ' . request()->user()->email . '.');
    
        return redirect(RouteServiceProvider::HOME);
    })
    ->middleware('throttle:6,1')
    ->name('verification.send');
});

