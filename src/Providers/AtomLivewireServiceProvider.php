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
            // auth
            'auth.login' => 'Auth\Login',
            'auth.register' => 'Auth\Register',
            'auth.register-form' => 'Auth\RegisterForm',
            'auth.reset-password' => 'Auth\ResetPassword',
            'auth.forgot-password' => 'Auth\ForgotPassword',

            // dashboard
            'app.dashboard' => 'App\Dashboard',

            // onboarding
            'app.onboarding' => 'App\Onboarding',

            // invitation
            'atom.app.invitation.create' => 'App\Invitation\Create',
            'atom.app.invitation.update' => 'App\Invitation\Update',
            'atom.app.invitation.pending' => 'App\Invitation\Pending',
            'atom.app.invitation.listing' => 'App\Invitation\Listing',

            // signup
            // 'atom.app.signup.listing' => 'App\Signup\Listing',
            // 'atom.app.signup.update' => 'App\Signup\Update',
            // 'atom.app.signup.info' => 'App\Signup\Info',

            // tax
            // 'atom.app.tax.listing' => 'App\Tax\Listing',
            // 'atom.app.tax.create' => 'App\Tax\Create',
            // 'atom.app.tax.update' => 'App\Tax\Update',
            // 'atom.app.tax.form' => 'App\Tax\Form',

            // blog
            // 'atom.app.blog.listing' => 'App\Blog\Listing',
            // 'atom.app.blog.create' => 'App\Blog\Create',
            // 'atom.app.blog.update' => 'App\Blog\Update',
            // 'atom.app.blog.form' => 'App\Blog\Form',
            // 'atom.app.blog.setting' => 'App\Blog\Setting',

            // enquiry
            'app.enquiry' => 'App\Enquiry\Index',
            'app.enquiry.update' => 'App\Enquiry\Update',

            // banner
            'app.banner' => 'App\Banner\Index',
            'app.banner.update' => 'App\Banner\Update',

            // email
            // 'atom.app.email.form-modal' => 'App\Email\FormModal',

            // ticket
            'atom.app.ticket.listing' => 'App\Ticket\Listing',
            'atom.app.ticket.create' => 'App\Ticket\Create',
            'atom.app.ticket.update' => 'App\Ticket\Update',
            'atom.app.ticket.comments' => 'App\Ticket\Comments',

            // settings
            'app.settings.index' => 'App\Settings\Index',
            'app.settings.login' => 'App\Settings\Login',
            'app.settings.password' => 'App\Settings\Password',

            // settings > user
            'app.settings.user' => 'App\Settings\User\Index',
            'app.settings.user.form' => 'App\Settings\User\Form',
            // 'app.settings.user.permission' => 'App\Settings\User\Permission',
            // 'app.settings.user.visibility' => 'App\Settings\User\Visibility',

            // settings > role
            'app.settings.role' => 'App\Settings\Role\Index',
            'app.settings.role.form' => 'App\Settings\Role\Form',

            // settings > file
            'app.settings.file' => 'App\Settings\File\Index',
            'app.settings.file.form' => 'App\Settings\File\Form',

            // settings > label
            'app.settings.label' => 'App\Settings\Label\Index',
            'app.settings.label.form' => 'App\Settings\Label\Form',
            'app.settings.label.listing' => 'App\Settings\Label\Listing',

            // settings > page
            'app.settings.page' => 'App\Settings\Page\Index',
            'app.settings.page.form' => 'App\Settings\Page\Form',
            'app.settings.page.update' => 'App\Settings\Page\Update',

            // settings > website
            'app.settings.website.profile' => 'App\Settings\Website\Profile',
            'app.settings.website.seo' => 'App\Settings\Website\Seo',
            'app.settings.website.analytics' => 'App\Settings\Website\Analytics',
            'app.settings.website.social-media' => 'App\Settings\Website\SocialMedia',
            'app.settings.website.announcement' => 'App\Settings\Website\Announcement',
            'app.settings.website.announcement-modal' => 'App\Settings\Website\AnnouncementModal',
            'app.settings.website.popup' => 'App\Settings\Website\Popup',

            // settings > integration
            'app.settings.integration.email' => 'App\Settings\Integration\Email',
            'app.settings.integration.storage' => 'App\Settings\Integration\Storage',
            'app.settings.integration.social-login' => 'App\Settings\Integration\SocialLogin',
            'app.settings.integration.payment' => 'App\Settings\Integration\Payment\Index',
            'app.settings.integration.payment.stripe' => 'App\Settings\Integration\Payment\Stripe',
            'app.settings.integration.payment.gkash' => 'App\Settings\Integration\Payment\Gkash',
            'app.settings.integration.payment.ozopay' => 'App\Settings\Integration\Payment\Ozopay',
            'app.settings.integration.payment.ipay' => 'App\Settings\Integration\Payment\Ipay',
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