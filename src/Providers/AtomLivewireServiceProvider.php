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
            'atom.web' => 'Web\Index',
            'atom.web.blog' => 'Web\Blog',
            'atom.web.contact-us' => 'Web\ContactUs\Index',
            'atom.web.contact-us.thank-you' => 'Web\ContactUs\ThankYou',

            // auth
            'atom.auth.login' => 'Auth\Login',
            'atom.auth.register' => 'Auth\Register',
            'atom.auth.register-form' => 'Auth\RegisterForm',
            'atom.auth.reset-password' => 'Auth\ResetPassword',
            'atom.auth.forgot-password' => 'Auth\ForgotPassword',

            // dashboard
            'atom.app.dashboard' => 'App\Dashboard',

            // account
            'atom.app.account.listing' => 'App\Account\Listing',
            'atom.app.account.update' => 'App\Account\Update\Index',
            'atom.app.account.update.register' => 'App\Account\Update\Register',

            // account payments
            'atom.app.account-payment.listing' => 'App\AccountPayment\Listing',
            'atom.app.account-payment.update' => 'App\AccountPayment\Update',

            // billing
            'atom.app.billing.home' => 'App\Billing\Index',
            'atom.app.billing.plans' => 'App\Billing\Plans',
            'atom.app.billing.checkout' => 'App\Billing\Checkout',
            'atom.app.billing.current-subscriptions' => 'App\Billing\CurrentSubscriptions',
            'atom.app.billing.cancel-auto-billing-modal' => 'App\Billing\CancelAutoBillingModal',

            // onboarding
            'atom.app.onboarding.home' => 'App\Onboarding\Index',
            'atom.app.onboarding.profile' => 'App\Onboarding\Profile',

            // blog
            'atom.app.blog.create' => 'App\Blog\Create',
            'atom.app.blog.listing' => 'App\Blog\Listing',
            'atom.app.blog.update' => 'App\Blog\Update\Index',
            'atom.app.blog.update.content' => 'App\Blog\Update\Content',
            'atom.app.blog.update.seo' => 'App\Blog\Update\Seo',
            'atom.app.blog.update.settings' => 'App\Blog\Update\Settings',

            // enquiry
            'atom.app.enquiry.listing' => 'App\Enquiry\Listing',
            'atom.app.enquiry.update' => 'App\Enquiry\Update',

            // product
            'atom.app.product.listing' => 'App\Product\Listing',
            'atom.app.product.create' => 'App\Product\Create',
            'atom.app.product.update' => 'App\Product\Update',
            'atom.app.product.form.info' => 'App\Product\Form\Info',
            'atom.app.product.form.image' => 'App\Product\Form\Image',

            // product variant
            'atom.app.product.variant.listing' => 'App\Product\Variant\Listing',
            'atom.app.product.variant.create' => 'App\Product\Variant\Create',
            'atom.app.product.variant.update' => 'App\Product\Variant\Update',
            'atom.app.product.variant.form' => 'App\Product\Variant\Form',

            // promotion
            'atom.app.promotion.listing' => 'App\Promotion\Listing',
            'atom.app.promotion.create' => 'App\Promotion\Create',
            'atom.app.promotion.update' => 'App\Promotion\Update',
            'atom.app.promotion.form' => 'App\Promotion\Form',

            // page
            'atom.app.page.listing' => 'App\Page\Listing',
            'atom.app.page.update' => 'App\Page\Update\Index',
            'atom.app.page.update.content' => 'App\Page\Update\Content',

            // plan
            'atom.app.plan.listing' => 'App\Plan\Listing',
            'atom.app.plan.create' => 'App\Plan\Create',
            'atom.app.plan.update' => 'App\Plan\Update\Index',
            'atom.app.plan.update.info' => 'App\Plan\Update\Info',
            'atom.app.plan.update.prices' => 'App\Plan\Update\Prices',

            // plan price
            'atom.app.plan-price.create' => 'App\PlanPrice\Create',
            'atom.app.plan-price.update' => 'App\PlanPrice\Update',
            'atom.app.plan-price.form' => 'App\PlanPrice\Form',

            // ticket
            'atom.app.ticketing.listing' => 'App\Ticketing\Listing',
            'atom.app.ticketing.create' => 'App\Ticketing\Create',
            'atom.app.ticketing.update' => 'App\Ticketing\Update',
            'atom.app.ticketing.comments' => 'App\Ticketing\Comments',

            // settings
            'atom.app.settings.index' => 'App\Settings\Index',
            'atom.app.settings.account.login' => 'App\Settings\Account\Login',
            'atom.app.settings.account.password' => 'App\Settings\Account\Password',
            'atom.app.settings.system.user' => 'App\Settings\System\User',
            'atom.app.settings.system.user-drawer' => 'App\Settings\System\UserDrawer',
            'atom.app.settings.system.user-form-modal' => 'App\Settings\System\UserFormModal',
            'atom.app.settings.system.role' => 'App\Settings\System\Role',
            'atom.app.settings.system.role-form-modal' => 'App\Settings\System\RoleFormModal',
            'atom.app.settings.system.team' => 'App\Settings\System\Team',
            'atom.app.settings.system.team-form-modal' => 'App\Settings\System\TeamFormModal',
            'atom.app.settings.system.file' => 'App\Settings\System\File',
            'atom.app.settings.system.file-form-modal' => 'App\Settings\System\FileFormModal',
            'atom.app.settings.system.permission-form-modal' => 'App\Settings\System\PermissionFormModal',
            'atom.app.settings.website.profile' => 'App\Settings\Website\Profile',
            'atom.app.settings.website.seo' => 'App\Settings\Website\Seo',
            'atom.app.settings.website.analytics' => 'App\Settings\Website\Analytics',
            'atom.app.settings.website.social-media' => 'App\Settings\Website\SocialMedia',
            'atom.app.settings.website.announcement' => 'App\Settings\Website\Announcement',
            'atom.app.settings.integration.email' => 'App\Settings\Integration\Email',
            'atom.app.settings.integration.storage' => 'App\Settings\Integration\Storage',
            'atom.app.settings.integration.payment' => 'App\Settings\Integration\Payment\Index',
            'atom.app.settings.integration.payment.stripe' => 'App\Settings\Integration\Payment\Stripe',
            'atom.app.settings.integration.payment.gkash' => 'App\Settings\Integration\Payment\Gkash',
            'atom.app.settings.integration.payment.ozopay' => 'App\Settings\Integration\Payment\Ozopay',
            'atom.app.settings.integration.payment.ipay' => 'App\Settings\Integration\Payment\Ipay',
            
            // preferences
            'atom.app.preferences.label' => 'App\Preferences\Label',
            'atom.app.preferences.label-form-modal' => 'App\Preferences\LabelFormModal',
            'atom.app.preferences.tax' => 'App\Preferences\Tax',
            'atom.app.preferences.tax-form-modal' => 'App\Preferences\TaxFormModal',
        ];

        foreach ($components as $name => $class) {
            Livewire::component($name, 'Jiannius\\Atom\\Http\\Livewire\\'.$class);
        }
    }
}