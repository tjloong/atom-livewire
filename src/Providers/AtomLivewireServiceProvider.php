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
            'web.page' => 'Web\Page',
            'web.contact-us' => 'Web\ContactUs',

            'onboarding' => 'Onboarding\Index',
            'onboarding.completed' => 'Onboarding\Completed',

            'auth.login' => 'Auth\Login',
            'auth.register' => 'Auth\Register',
            'auth.register-form' => 'Auth\RegisterForm',
            'auth.reset-password' => 'Auth\ResetPassword',
            'auth.forgot-password' => 'Auth\ForgotPassword',

            'app.share' => 'App\Share',
            'app.dashboard' => 'App\Dashboard',
            'app.footprint' => 'App\Footprint',

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
            'app.announcement.update' => 'App\Announcement\Update',
            'app.announcement.listing' => 'App\Announcement\Listing',

            'app.popup' => 'App\Popup\Index',
            'app.popup.update' => 'App\Popup\Update',
            'app.popup.listing' => 'App\Popup\Listing',

            'app.banner' => 'App\Banner\Index',
            'app.banner.update' => 'App\Banner\Update',
            'app.banner.listing' => 'App\Banner\Listing',

            'app.audit' => 'App\Audit\Index',
            'app.audit.show' => 'App\Audit\Show',
            'app.audit.listing' => 'App\Audit\Listing',

            'app.sendmail' => 'App\Sendmail\Index',
            'app.sendmail.edit' => 'App\Sendmail\Edit',
            'app.sendmail.listing' => 'App\Sendmail\Listing',
            'app.sendmail.composer' => 'App\Sendmail\Composer',

            'app.label.edit' => 'App\Label\Edit',
            'app.label.listing' => 'App\Label\Listing',
            
            'app.user.edit' => 'App\User\Edit',
            'app.user.listing' => 'App\User\Listing',
            'app.user.permission' => 'App\User\Permission',

            'app.file.edit' => 'App\File\Edit',
            'app.file.library' => 'App\File\Library',

            'app.settings.index' => 'App\Settings\Index',
            'app.settings.site' => 'App\Settings\Site',
            'app.settings.user' => 'App\Settings\User',
            'app.settings.file' => 'App\Settings\File',
            'app.settings.label' => 'App\Settings\Label',
            'app.settings.profile' => 'App\Settings\Profile\Index',
            'app.settings.profile.login' => 'App\Settings\Profile\Login',
            'app.settings.profile.password' => 'App\Settings\Profile\Password',
            'app.settings.integration.email' => 'App\Settings\Integration\Email',
            'app.settings.integration.storage' => 'App\Settings\Integration\Storage',
            'app.settings.integration.social-login' => 'App\Settings\Integration\SocialLogin',
            'app.settings.integration.finexus' => 'App\Settings\Integration\Finexus',
            'app.settings.integration.stripe' => 'App\Settings\Integration\Stripe',
            'app.settings.integration.ipay' => 'App\Settings\Integration\Ipay',
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