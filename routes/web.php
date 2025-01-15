<?php

use Illuminate\Support\Facades\Route;
use Jiannius\Atom\Atom;

Route::middleware('web')->group(function () {
    if (config('atom.auth.login') !== false) {
        Route::get('login', \Jiannius\Atom\Livewire\Auth\Login::class)->name('login');
        Route::get('logout', \Jiannius\Atom\Livewire\Auth\Logout::class)->middleware('auth')->name('logout');
    }

    if (config('atom.auth.password') !== false) {
        Route::get('reset-password', \Jiannius\Atom\Livewire\Auth\ResetPassword::class)->name('password.reset');
        Route::get('forgot-password', \Jiannius\Atom\Livewire\Auth\ForgotPassword::class)->middleware('guest')->name('password.forgot');
    }

    if (config('atom.auth.register') !== false) {
        Route::get('register', \Jiannius\Atom\Livewire\Auth\Register::class)->middleware('guest')->name('register');
    }

    if (config('atom.auth.socialite') !== false) {
        Route::get('__auth/{provider}/redirect', [\Jiannius\Atom\Http\Controllers\SocialiteController::class, 'redirect'])->name('socialite.redirect');
        Route::get('__auth/{provider}/callback', [\Jiannius\Atom\Http\Controllers\SocialiteController::class, 'callback'])->name('socialite.callback');
    }
});

Route::post('__file/upload', [\Jiannius\Atom\Http\Controllers\FileController::class, 'upload'])->name('__file.upload');
Route::post('__action/{action}', fn ($action) => response()->json(Atom::action($action, request()->all())));
Route::get('__icons.js', fn () => \Jiannius\Atom\Services\Icon::jsResponse())->name('__icons.js');
Route::get('__lang.js', fn () => \Jiannius\Atom\Services\Lang::jsResponse())->withoutMiddleware('web')->name('__lang.js');
Route::get('__lang/{lang?}', function ($lang = null) {
    session()->put('__lang', $lang ?? user()?->settings('locale') ?? config('atom.locale') ?? 'en');
    return redirect(user()?->home() ?? '/');
})->name('__lang');
