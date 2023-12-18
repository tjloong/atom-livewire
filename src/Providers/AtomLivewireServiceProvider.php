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
            // web
            'web.blog' => 'Web\Blog',
            'web.page' => 'Web\Page',
            'web.contact-us' => 'Web\ContactUs',

            // auth
            'auth.login' => 'Auth\Login',
            'auth.register' => 'Auth\Register',
            'auth.register-form' => 'Auth\RegisterForm',
            'auth.reset-password' => 'Auth\ResetPassword',
            'auth.forgot-password' => 'Auth\ForgotPassword',

            // dashboard
            'app.dashboard' => 'App\Dashboard',

            // onboarding
            'app.onboarding' => 'App\Onboarding\Index',
            'app.onboarding.completed' => 'App\Onboarding\Completed',

            // send email
            'app.send-email' => 'App\SendEmail',

            // share
            'app.share' => 'App\Share',
            'app.share.update' => 'App\Share\Update',

            // signup
            'app.signup' => 'App\Signup\Index',
            'app.signup.listing' => 'App\Signup\Listing',
            'app.signup.update' => 'App\Signup\Update',

            // blog
            'app.blog' => 'App\Blog\Index',
            'app.blog.update' => 'App\Blog\Update',
            'app.blog.listing' => 'App\Blog\Listing',

            // enquiry
            'app.enquiry' => 'App\Enquiry\Index',
            'app.enquiry.update' => 'App\Enquiry\Update',
            'app.enquiry.listing' => 'App\Enquiry\Listing',

            // page
            'app.page' => 'App\Page\Index',
            'app.page.update' => 'App\Page\Update',
            'app.page.listing' => 'App\Page\Listing',

            // announcement
            'app.announcement' => 'App\Announcement\Index',
            'app.announcement.update' => 'App\Announcement\Update',
            'app.announcement.listing' => 'App\Announcement\Listing',

            // popup
            'app.popup' => 'App\Popup\Index',
            'app.popup.update' => 'App\Popup\Update',
            'app.popup.listing' => 'App\Popup\Listing',

            // banner
            'app.banner' => 'App\Banner\Index',
            'app.banner.update' => 'App\Banner\Update',
            'app.banner.listing' => 'App\Banner\Listing',

            // settings
            'app.settings.index' => 'App\Settings\Index',
            'app.settings.site' => 'App\Settings\Site',
            'app.settings.login' => 'App\Settings\Login',
            'app.settings.password' => 'App\Settings\Password',

            // settings > user
            'app.settings.user' => 'App\Settings\User\Index',
            'app.settings.user.update' => 'App\Settings\User\Update',
            'app.settings.user.listing' => 'App\Settings\User\Listing',

            // settings > file
            'app.settings.file' => 'App\Settings\File\Index',
            'app.settings.file.update' => 'App\Settings\File\Update',
            'app.settings.file.library' => 'App\Settings\File\Library',
            'app.settings.file.listing' => 'App\Settings\File\Listing',

            // settings > label
            'app.settings.label' => 'App\Settings\Label\Index',
            'app.settings.label.update' => 'App\Settings\Label\Update',
            'app.settings.label.listing' => 'App\Settings\Label\Listing',

            // settings > integration
            'app.settings.integration.email' => 'App\Settings\Integration\Email',
            'app.settings.integration.storage' => 'App\Settings\Integration\Storage',
            'app.settings.integration.social-login' => 'App\Settings\Integration\SocialLogin',
            'app.settings.integration.revenue-monster' => 'App\Settings\Integration\RevenueMonster',
            'app.settings.integration.stripe' => 'App\Settings\Integration\Stripe',
            'app.settings.integration.ipay' => 'App\Settings\Integration\Ipay',
            // 'app.settings.integration.payment.gkash' => 'App\Settings\Integration\Payment\Gkash',
            // 'app.settings.integration.payment.ozopay' => 'App\Settings\Integration\Payment\Ozopay',
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