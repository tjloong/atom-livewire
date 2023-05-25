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
            'atom.web.contact-us' => 'Web\ContactUs',
            'atom.web.thank' => 'Web\Thank\Index',
            'atom.web.thank.payment' => 'Web\Thank\Payment',
            'atom.web.thank.plan-payment' => 'Web\Thank\PlanPayment',
            'atom.web.thank.contact-us' => 'Web\Thank\ContactUs',

            // auth
            'atom.auth.login' => 'Auth\Login',
            'atom.auth.register' => 'Auth\Register',
            'atom.auth.register-form' => 'Auth\RegisterForm',
            'atom.auth.reset-password' => 'Auth\ResetPassword',
            'atom.auth.forgot-password' => 'Auth\ForgotPassword',

            // dashboard
            'atom.app.dashboard' => 'App\Dashboard',

            // onboarding
            'atom.app.onboarding' => 'App\Onboarding',

            // user
            'atom.app.user.listing' => 'App\User\Listing',
            'atom.app.user.create' => 'App\User\Create',
            'atom.app.user.update' => 'App\User\Update',
            'atom.app.user.role' => 'App\User\Role',
            'atom.app.user.team' => 'App\User\Team',
            'atom.app.user.permission' => 'App\User\Permission',
            'atom.app.user.visibility' => 'App\User\Visibility',
            'atom.app.user.btn-block' => 'App\User\BtnBlock',
            'atom.app.user.btn-delete' => 'App\User\BtnDelete',

            // tenant
            'atom.app.tenant.create' => 'App\Tenant\Create',
            'atom.app.tenant.form' => 'App\Tenant\Form',
            'atom.app.tenant.switcher' => 'App\Tenant\Switcher',

            // invitation
            'atom.app.invitation.create' => 'App\Invitation\Create',
            'atom.app.invitation.update' => 'App\Invitation\Update',
            'atom.app.invitation.pending' => 'App\Invitation\Pending',
            'atom.app.invitation.listing' => 'App\Invitation\Listing',

            // signup
            'atom.app.signup.listing' => 'App\Signup\Listing',
            'atom.app.signup.update' => 'App\Signup\Update',
            'atom.app.signup.info' => 'App\Signup\Info',

            // role
            'atom.app.role.listing' => 'App\Role\Listing',
            'atom.app.role.create' => 'App\Role\Create',
            'atom.app.role.update' => 'App\Role\Update',
            'atom.app.role.form' => 'App\Role\Form',

            // team
            'atom.app.team.listing' => 'App\Team\Listing',
            'atom.app.team.create' => 'App\Team\Create',
            'atom.app.team.update' => 'App\Team\Update',
            'atom.app.team.form' => 'App\Team\Form',

            // file
            'atom.app.file.listing' => 'App\File\Listing',
            'atom.app.file.form-modal' => 'App\File\FormModal',

            // tax
            'atom.app.tax.listing' => 'App\Tax\Listing',
            'atom.app.tax.create' => 'App\Tax\Create',
            'atom.app.tax.update' => 'App\Tax\Update',
            'atom.app.tax.form' => 'App\Tax\Form',

            // label
            'atom.app.label.listing' => 'App\Label\Listing',
            'atom.app.label.create' => 'App\Label\Create',
            'atom.app.label.update' => 'App\Label\Update',
            'atom.app.label.form' => 'App\Label\Form',
            'atom.app.label.children' => 'App\Label\Children',

            // blog
            'atom.app.blog.listing' => 'App\Blog\Listing',
            'atom.app.blog.create' => 'App\Blog\Create',
            'atom.app.blog.update' => 'App\Blog\Update',
            'atom.app.blog.form' => 'App\Blog\Form',
            'atom.app.blog.setting' => 'App\Blog\Setting',

            // enquiry
            'atom.app.enquiry.listing' => 'App\Enquiry\Listing',
            'atom.app.enquiry.update' => 'App\Enquiry\Update',

            // banner
            'atom.app.banner.listing' => 'App\Banner\Listing',
            'atom.app.banner.create' => 'App\Banner\Create',
            'atom.app.banner.update' => 'App\Banner\Update',
            'atom.app.banner.form' => 'App\Banner\Form',

            // contact
            'atom.app.contact.listing' => 'App\Contact\Listing',
            'atom.app.contact.create' => 'App\Contact\Create',
            'atom.app.contact.update' => 'App\Contact\Update',
            'atom.app.contact.form' => 'App\Contact\Form',
            'atom.app.contact.view' => 'App\Contact\View',
            'atom.app.contact.person.listing' => 'App\Contact\Person\Listing',
            'atom.app.contact.person.create' => 'App\Contact\Person\Create',
            'atom.app.contact.person.update' => 'App\Contact\Person\Update',
            'atom.app.contact.person.form' => 'App\Contact\Person\Form',

            // product
            'atom.app.product.listing' => 'App\Product\Listing',
            'atom.app.product.create' => 'App\Product\Create',
            'atom.app.product.update' => 'App\Product\Update',
            'atom.app.product.form' => 'App\Product\Form',
            'atom.app.product.image' => 'App\Product\Image',

            // product variant
            'atom.app.product.variant.listing' => 'App\Product\Variant\Listing',
            'atom.app.product.variant.create' => 'App\Product\Variant\Create',
            'atom.app.product.variant.update' => 'App\Product\Variant\Update',
            'atom.app.product.variant.form' => 'App\Product\Variant\Form',

            // shipping
            'atom.app.shipping.listing' => 'App\Shipping\Listing',
            'atom.app.shipping.create' => 'App\Shipping\Create',
            'atom.app.shipping.update' => 'App\Shipping\Update',
            'atom.app.shipping.form' => 'App\Shipping\Form',

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
            'atom.app.document.view.body' => 'App\Document\View\Body',
            'atom.app.document.view.converted' => 'App\Document\View\Converted',
            'atom.app.document.view.attachment' => 'App\Document\View\Attachment',
            'atom.app.document.view.split' => 'App\Document\View\Split',
            'atom.app.document.view.payment' => 'App\Document\View\Payment',
            'atom.app.document.view.email-modal' => 'App\Document\View\EmailModal',
            'atom.app.document.form' => 'App\Document\Form\Index',
            'atom.app.document.form.item' => 'App\Document\Form\Item',
            'atom.app.document.form.total' => 'App\Document\Form\Total',
            'atom.app.document.form.product-modal' => 'App\Document\Form\ProductModal',
            'atom.app.document.payment.create' => 'App\Document\Payment\Create',
            'atom.app.document.payment.update' => 'App\Document\Payment\Update',
            'atom.app.document.payment.form' => 'App\Document\Payment\Form',

            // page
            'atom.app.page.listing' => 'App\Page\Listing',
            'atom.app.page.update' => 'App\Page\Update',
            'atom.app.page.content' => 'App\Page\Content',

            // plan
            'atom.app.plan.listing' => 'App\Plan\Listing',
            'atom.app.plan.create' => 'App\Plan\Create',
            'atom.app.plan.update' => 'App\Plan\Update',
            'atom.app.plan.form' => 'App\Plan\Form',
            'atom.app.plan.price-modal' => 'App\Plan\PriceModal',

            // plan subscription
            'atom.app.plan.subscription.listing' => 'App\Plan\Subscription\Listing',
            'atom.app.plan.subscription.create' => 'App\Plan\Subscription\Create',
            'atom.app.plan.subscription.update' => 'App\Plan\Subscription\Update',
            
            // plan payment
            'atom.app.plan.payment.listing' => 'App\Plan\Payment\Listing',
            'atom.app.plan.payment.update' => 'App\Plan\Payment\Update',
            
            // billing
            'atom.app.billing' => 'App\Billing\Index',
            'atom.app.billing.checkout' => 'App\Billing\Checkout',
            'atom.app.billing.receipt' => 'App\Billing\Receipt',
            'atom.app.billing.subscription-modal' => 'App\Billing\SubscriptionModal',
            'atom.app.billing.cancel-auto-renew-modal' => 'App\Billing\CancelAutoRenewModal',

            // ticket
            'atom.app.ticketing.listing' => 'App\Ticketing\Listing',
            'atom.app.ticketing.create' => 'App\Ticketing\Create',
            'atom.app.ticketing.update' => 'App\Ticketing\Update',
            'atom.app.ticketing.comments' => 'App\Ticketing\Comments',

            // settings
            'atom.app.settings.index' => 'App\Settings\Index',
            'atom.app.settings.login' => 'App\Settings\Login',
            'atom.app.settings.password' => 'App\Settings\Password',
            'atom.app.settings.website.profile' => 'App\Settings\Website\Profile',
            'atom.app.settings.website.seo' => 'App\Settings\Website\Seo',
            'atom.app.settings.website.analytics' => 'App\Settings\Website\Analytics',
            'atom.app.settings.website.social-media' => 'App\Settings\Website\SocialMedia',
            'atom.app.settings.website.announcement' => 'App\Settings\Website\Announcement',
            'atom.app.settings.website.announcement-modal' => 'App\Settings\Website\AnnouncementModal',
            'atom.app.settings.website.popup' => 'App\Settings\Website\Popup',
            'atom.app.settings.integration.email' => 'App\Settings\Integration\Email',
            'atom.app.settings.integration.storage' => 'App\Settings\Integration\Storage',
            'atom.app.settings.integration.social-login' => 'App\Settings\Integration\SocialLogin',
            'atom.app.settings.integration.payment' => 'App\Settings\Integration\Payment\Index',
            'atom.app.settings.integration.payment.stripe' => 'App\Settings\Integration\Payment\Stripe',
            'atom.app.settings.integration.payment.gkash' => 'App\Settings\Integration\Payment\Gkash',
            'atom.app.settings.integration.payment.ozopay' => 'App\Settings\Integration\Payment\Ozopay',
            'atom.app.settings.integration.payment.ipay' => 'App\Settings\Integration\Payment\Ipay',
        ];

        foreach ($components as $name => $class) {
            $ns = 'Jiannius\\Atom\\Http\\Livewire\\'.$class;

            if (file_exists(atom_ns_path($ns))) {
                Livewire::component($name, $ns);
            }
        }
    }
}