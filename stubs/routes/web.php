<?php

use Illuminate\Support\Facades\Route;

Route::prefix('app')->middleware('auth')->group(function () {
    Route::get('/', function () {
        session()->reflash();
        return redirect()->route('dashboard');
    });

    /**
     * Dashboard
     */
    Route::get('dashboard',  App\Http\Livewire\App\Dashboard::class)->name('dashboard');

    /**
     * Blogs
     */
    Route::prefix('blog')->group(function () {
        Route::get('listing',  App\Http\Livewire\App\Blog\Listing::class)->name('blog.listing');
        Route::get('create',  App\Http\Livewire\App\Blog\Create::class)->name('blog.create');
        Route::get('{blog}', App\Http\Livewire\App\Blog\Update::class)->name('blog.update');
    });

    /**
     * Enquiries
     */
    Route::prefix('enquiry')->group(function () {
        Route::get('listing',  App\Http\Livewire\App\Enquiry\Listing::class)->name('enquiry.listing');
        Route::get('{enquiry}', App\Http\Livewire\App\Enquiry\Update::class)->name('enquiry.update');
    });

    /**
     * Pages
     */
    Route::prefix('page')->group(function () {
        Route::get('listing',  App\Http\Livewire\App\Page\Listing::class)->name('page.listing');
        Route::get('{page}', App\Http\Livewire\App\Page\Update::class)->name('page.update');
    });

    /**
     * Roles
     */
    Route::prefix('role')->group(function () {
        Route::get('listing',  App\Http\Livewire\App\Role\Listing::class)->name('role.listing');
        Route::get('create',  App\Http\Livewire\App\Role\Create::class)->name('role.create');
        Route::get('{role}', App\Http\Livewire\App\Role\Update::class)->name('role.update');
    });

    /**
     * Users
     */
    Route::prefix('user')->group(function () {
        Route::get('account',  App\Http\Livewire\App\User\Account::class)->name('user.account');
        Route::get('listing',  App\Http\Livewire\App\User\Listing::class)->name('user.listing');
        Route::get('create',  App\Http\Livewire\App\User\Create::class)->name('user.create');
        Route::get('{user}', App\Http\Livewire\App\User\Update::class)->name('user.update');
    });

    /**
     * Teams
     */
    Route::prefix('team')->group(function () {
        Route::get('listing',  App\Http\Livewire\App\Team\Listing::class)->name('team.listing');
        Route::get('create',  App\Http\Livewire\App\Team\Create::class)->name('team.create');
        Route::get('{team}', App\Http\Livewire\App\Team\Update::class)->name('team.update');
    });

    /**
     * Label
     */
    Route::prefix('label')->group(function () {
        Route::get('listing/{type}',  App\Http\Livewire\App\Label\Listing::class)->name('label.listing');
        Route::get('create/{type}',  App\Http\Livewire\App\Label\Create::class)->name('label.create');
        Route::get('{label}', App\Http\Livewire\App\Label\Update::class)->name('label.update');
    });

    /**
     * Files
     */
    Route::get('file/listing', App\Http\Livewire\App\File\Listing::class)->name('file.listing');

    /**
     * Site Settings
     */
    Route::get('site-settings', App\Http\Livewire\App\SiteSettings\Update::class)->name('site-settings.update');
});

Route::get('blogs/{slug?}', App\Http\Livewire\Web\Blog::class)->name('blog.show');
Route::get('contact/{slug?}', App\Http\Livewire\Web\Contact::class)->name('contact');
Route::get('/', App\Http\Livewire\Web\Home::class)->name('home');
