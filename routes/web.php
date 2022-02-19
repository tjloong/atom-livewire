<?php

use Jiannius\Atom\Models\Page;
use Illuminate\Support\Facades\Route;

if (!config('atom.static_site')) {
    define_route('__sitemap', 'SitemapController@index', '__sitemap');
    define_route('__export/{filename}', 'ExportController@download', '__export');

    Route::prefix('app')->middleware('auth')->group(function() {
        Route::get('/', fn() => redirect()->route('dashboard'))->name('app.home');

        /**
         * Dashboard
         */
        Route::get('dashboard', Jiannius\Atom\Http\Livewire\App\Dashboard::class)->name('dashboard');

        /**
         * Blogs
         */
        if (enabled_feature('blogs')) {
            Route::prefix('blog')->group(function () {
                try { Route::get('listing', App\Http\Livewire\App\Blog\Listing::class)->name('blog.listing'); }
                catch (Exception $e) { Route::get('listing', Jiannius\Atom\Http\Livewire\App\Blog\Listing::class)->name('blog.listing'); }

                try { Route::get('create', App\Http\Livewire\App\Blog\Create::class)->name('blog.create'); }
                catch (Exception $e) { Route::get('create', Jiannius\Atom\Http\Livewire\App\Blog\Create::class)->name('blog.create'); }
                
                try { Route::get('{blog}/{tab?}', App\Http\Livewire\App\Blog\Update::class)->name('blog.update'); }
                catch (Exception $e) { Route::get('{blog}/{tab?}', Jiannius\Atom\Http\Livewire\App\Blog\Update::class)->name('blog.update'); }
            });
        }

        /**
         * Enquiries
         */
        if (enabled_feature('enquiries')) {
            Route::prefix('enquiry')->group(function () {
                Route::get('listing',  Jiannius\Atom\Http\Livewire\App\Enquiry\Listing::class)->name('enquiry.listing');
                Route::get('{enquiry}', Jiannius\Atom\Http\Livewire\App\Enquiry\Update::class)->name('enquiry.update');
            });
        }

        /**
         * Tickets
         */
        if (enabled_feature('tickets')) {
            Route::prefix('ticket')->group(function() {
                Route::get('listing', Jiannius\Atom\Http\Livewire\App\Ticket\Listing::class)->name('ticket.listing');
                Route::get('create', Jiannius\Atom\Http\Livewire\App\Ticket\Create::class)->name('ticket.create');
                Route::get('{ticket}', Jiannius\Atom\Http\Livewire\App\Ticket\Update::class)->name('ticket.update');
            });
        }

        /**
         * Pages
         */
        if (enabled_feature('pages')) {
            Route::prefix('page')->group(function () {
                Route::get('listing',  Jiannius\Atom\Http\Livewire\App\Page\Listing::class)->name('page.listing');
                Route::get('{id}', Jiannius\Atom\Http\Livewire\App\Page\Update::class)->name('page.update');
            });
        }
    
        /**
         * Teams
         */
        if (enabled_feature('teams')) {
            Route::prefix('team')->group(function () {
                Route::get('listing', Jiannius\Atom\Http\Livewire\App\Team\Listing::class)->name('team.listing');
                Route::get('create', Jiannius\Atom\Http\Livewire\App\Team\Create::class)->name('team.create');
                Route::get('{team}', Jiannius\Atom\Http\Livewire\App\Team\Update::class)->name('team.update');
            });
        }
    
        /**
         * Label
         */
        if (enabled_feature('labels')) {
            Route::prefix('label')->group(function () {
                Route::get('listing/{type?}', Jiannius\Atom\Http\Livewire\App\Label\Listing::class)->name('label.listing');
                Route::get('create/{type}', Jiannius\Atom\Http\Livewire\App\Label\Create::class)->name('label.create');
                Route::get('{id}', Jiannius\Atom\Http\Livewire\App\Label\Update::class)->name('label.update');
            });
        }
    
        /**
         * Site Settings
         */
        if (enabled_feature('site_settings')) {
            Route::get('site-settings/{category?}', Jiannius\Atom\Http\Livewire\App\SiteSettings\Update::class)->name('site-settings');
        }

        /**
         * Roles
         */
        Route::prefix('role')->group(function () {
            Route::get('listing', Jiannius\Atom\Http\Livewire\App\Role\Listing::class)->name('role.listing');
            Route::get('create', Jiannius\Atom\Http\Livewire\App\Role\Create::class)->name('role.create');
            Route::get('{role}', Jiannius\Atom\Http\Livewire\App\Role\Update::class)->name('role.update');
        });

        /**
         * Users
         */
        Route::prefix('user')->group(function () {
            Route::get('account', Jiannius\Atom\Http\Livewire\App\User\Account::class)->name('user.account');
            Route::get('listing', Jiannius\Atom\Http\Livewire\App\User\Listing::class)->name('user.listing');
            Route::get('create', Jiannius\Atom\Http\Livewire\App\User\Create::class)->name('user.create');
            Route::get('{user}', Jiannius\Atom\Http\Livewire\App\User\Update::class)->name('user.update');
        });
    
        /**
         * Files
         */
        Route::get('files', Jiannius\Atom\Http\Livewire\App\File\Listing::class)->name('files');
    });

}

if (enabled_feature('blogs')) {
    try { Route::get('blogs/{slug?}', App\Http\Livewire\Web\Blog::class)->name('blogs'); }
    catch (Exception $e) { Route::get('blogs/{slug?}', Jiannius\Atom\Http\Livewire\Web\Blog::class)->name('blogs'); }
}

Route::get('contact', Jiannius\Atom\Http\Livewire\Web\Contact::class)->name('contact');
Route::get('contact/thank-you', Jiannius\Atom\Http\Livewire\Web\ContactSent::class)->name('contact.sent');

if (enabled_feature('pages') && !app()->runningInConsole()) {
    $slugs = Page::getSlugs();

    Route::get('{slug}', \Jiannius\Atom\Http\Livewire\Web\Page::class)
        ->name('page')
        ->where(['slug' => '(' . implode('|', $slugs) . ')']);
}

Route::get('/', Jiannius\Atom\Http\Livewire\Web\Home::class)->name('home');