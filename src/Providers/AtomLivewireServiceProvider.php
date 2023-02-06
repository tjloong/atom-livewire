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

            // user
            'atom.app.user.listing' => 'App\User\Listing',
            'atom.app.user.create' => 'App\User\Create',
            'atom.app.user.update' => 'App\User\Update\Index',
            'atom.app.user.update.block' => 'App\User\Update\Block',
            'atom.app.user.update.delete' => 'App\User\Update\Delete',
            'atom.app.user.update.drawer' => 'App\User\Update\Drawer',
            'atom.app.user.form' => 'App\User\Form',

            // signup
            'atom.app.signup.listing' => 'App\Signup\Listing',
            'atom.app.signup.view' => 'App\Signup\View\Index',
            'atom.app.signup.view.info' => 'App\Signup\View\Info',

            // billing
            'atom.app.billing.view' => 'App\Billing\View',
            'atom.app.billing.plan' => 'App\Billing\Plan',
            'atom.app.billing.checkout' => 'App\Billing\Checkout',
            'atom.app.billing.current-subscriptions' => 'App\Billing\CurrentSubscriptions',
            'atom.app.billing.cancel-auto-billing-modal' => 'App\Billing\CancelAutoBillingModal',

            // account payment
            'atom.app.billing.payment.listing' => 'App\Billing\Payment\Listing',
            'atom.app.billing.payment.update' => 'App\Billing\Payment\Update',

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

            // contact
            'atom.app.contact.listing' => 'App\Contact\Listing',
            'atom.app.contact.create' => 'App\Contact\Create',
            'atom.app.contact.update' => 'App\Contact\Update',
            'atom.app.contact.view' => 'App\Contact\View\Index',
            'atom.app.contact.view.person' => 'App\Contact\View\Person',
            'atom.app.contact.form.info' => 'App\Contact\Form\Info',
            'atom.app.contact.form.person-modal' => 'App\Contact\Form\PersonModal',

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

            // document
            'atom.app.document.listing' => 'App\Document\Listing',
            'atom.app.document.create' => 'App\Document\Create',
            'atom.app.document.update' => 'App\Document\Update',
            'atom.app.document.split' => 'App\Document\Split',
            'atom.app.document.view' => 'App\Document\View\Index',
            'atom.app.document.view.action' => 'App\Document\View\Action',
            'atom.app.document.view.contact' => 'App\Document\View\Contact',
            'atom.app.document.view.info' => 'App\Document\View\Info',
            'atom.app.document.view.item' => 'App\Document\View\Item',
            'atom.app.document.view.total' => 'App\Document\View\Total',
            'atom.app.document.view.additional-info' => 'App\Document\View\AdditionalInfo',
            'atom.app.document.view.converted' => 'App\Document\View\Converted',
            'atom.app.document.view.attachment' => 'App\Document\View\Attachment',
            'atom.app.document.view.split' => 'App\Document\View\Split',
            'atom.app.document.view.payment' => 'App\Document\View\Payment',
            'atom.app.document.form' => 'App\Document\Form\Index',
            'atom.app.document.form.contact' => 'App\Document\Form\Contact',
            'atom.app.document.form.info' => 'App\Document\Form\Info',
            'atom.app.document.form.item' => 'App\Document\Form\Item',
            'atom.app.document.form.total' => 'App\Document\Form\Total',
            'atom.app.document.form.footer' => 'App\Document\Form\Footer',
            'atom.app.document.form.additional-info' => 'App\Document\Form\AdditionalInfo',
            'atom.app.document.form.product-modal' => 'App\Document\Form\ProductModal',
            'atom.app.document.form.currency-modal' => 'App\Document\Form\CurrencyModal',
            'atom.app.document.form.email-modal' => 'App\Document\Form\EmailModal',
            'atom.app.document.payment.create' => 'App\Document\Payment\Create',
            'atom.app.document.payment.update' => 'App\Document\Payment\Update',
            'atom.app.document.payment.form' => 'App\Document\Payment\Form',

            // page
            'atom.app.page.listing' => 'App\Page\Listing',
            'atom.app.page.update' => 'App\Page\Update\Index',
            'atom.app.page.update.content' => 'App\Page\Update\Content',

            // plan
            'atom.app.plan.listing' => 'App\Plan\Listing',
            'atom.app.plan.create' => 'App\Plan\Create',
            'atom.app.plan.update' => 'App\Plan\Update',
            'atom.app.plan.form' => 'App\Plan\Form',

            // plan price
            'atom.app.plan.price.listing' => 'App\Plan\Price\Listing',
            'atom.app.plan.price.create' => 'App\Plan\Price\Create',
            'atom.app.plan.price.update' => 'App\Plan\Price\Update',
            'atom.app.plan.price.form' => 'App\Plan\Price\Form',

            // ticket
            'atom.app.ticketing.listing' => 'App\Ticketing\Listing',
            'atom.app.ticketing.create' => 'App\Ticketing\Create',
            'atom.app.ticketing.update' => 'App\Ticketing\Update',
            'atom.app.ticketing.comments' => 'App\Ticketing\Comments',

            // settings
            'atom.app.settings.index' => 'App\Settings\Index',
            'atom.app.settings.login' => 'App\Settings\Login',
            'atom.app.settings.password' => 'App\Settings\Password',
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
            'atom.app.settings.integration.social-login' => 'App\Settings\Integration\SocialLogin',
            'atom.app.settings.integration.payment' => 'App\Settings\Integration\Payment\Index',
            'atom.app.settings.integration.payment.stripe' => 'App\Settings\Integration\Payment\Stripe',
            'atom.app.settings.integration.payment.gkash' => 'App\Settings\Integration\Payment\Gkash',
            'atom.app.settings.integration.payment.ozopay' => 'App\Settings\Integration\Payment\Ozopay',
            'atom.app.settings.integration.payment.ipay' => 'App\Settings\Integration\Payment\Ipay',
            
            // preferences
            'atom.app.preferences.label' => 'App\Preferences\Label\Index',
            'atom.app.preferences.label.children' => 'App\Preferences\Label\Children',
            'atom.app.preferences.label.form-modal' => 'App\Preferences\Label\FormModal',
            'atom.app.preferences.tax' => 'App\Preferences\Tax',
            'atom.app.preferences.tax-form-modal' => 'App\Preferences\TaxFormModal',
        ];

        foreach ($components as $name => $class) {
            Livewire::component($name, 'Jiannius\\Atom\\Http\\Livewire\\'.$class);
        }
    }
}