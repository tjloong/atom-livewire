<?php

namespace Jiannius\Atom\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;

class AtomLivewireServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $components = [
            'web.blog' => 'Web\Blog',

            'onboarding' => 'Onboarding\Index',
            'onboarding.completed' => 'Onboarding\Completed',

            'app.signup' => 'App\Signup\Index',
            'app.signup.edit' => 'App\Signup\Edit',
            'app.signup.listing' => 'App\Signup\Listing',

            'app.blog' => 'App\Blog\Index',
            'app.blog.edit' => 'App\Blog\Edit',
            'app.blog.listing' => 'App\Blog\Listing',

            'app.enquiry' => 'App\Enquiry\Index',
            'app.enquiry.edit' => 'App\Enquiry\Edit',
            'app.enquiry.listing' => 'App\Enquiry\Listing',

            'app.page' => 'App\Page\Index',
            'app.page.edit' => 'App\Page\Edit',
            'app.page.listing' => 'App\Page\Listing',

            'app.announcement' => 'App\Announcement\Index',
            'app.announcement.edit' => 'App\Announcement\Edit',
            'app.announcement.listing' => 'App\Announcement\Listing',

            'app.popup' => 'App\Popup\Index',
            'app.popup.edit' => 'App\Popup\Edit',
            'app.popup.listing' => 'App\Popup\Listing',

            'app.banner' => 'App\Banner\Index',
            'app.banner.edit' => 'App\Banner\Edit',
            'app.banner.listing' => 'App\Banner\Listing',

            'app.audit' => 'App\Audit\Index',
            'app.audit.show' => 'App\Audit\Show',
            'app.audit.listing' => 'App\Audit\Listing',

            'app.sendmail' => 'App\Sendmail\Index',
            'app.sendmail.edit' => 'App\Sendmail\Edit',
            'app.sendmail.listing' => 'App\Sendmail\Listing',

            'app.label.edit' => 'App\Label\Edit',
            'app.label.listing' => 'App\Label\Listing',
        ];

        foreach ($components as $name => $class) {
            if (
                $ns = collect([
                    "App\Http\Livewire\\$class",
                    "Jiannius\Atom\Http\Livewire\\$class",
                ])->first(fn($s) => class_exists($s))
            ) {
                Livewire::component($name, $ns);
            }
        }
    }
}