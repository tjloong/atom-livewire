<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AtomComponentServiceProvider extends ServiceProvider
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
            'seo',
            'icon',
            'logo',
            'alert',
            'badge',
            'image',
            'avatar',
            'loader',
            'drawer',
            'pricing',
            'lightbox',
            'statsbox',
            'checkbox',
            'file-card',
            'cdn-scripts',
            'empty-state',
            'alpine-data',
            'breadcrumbs',
            'social-share',
            'announcements',
            'payment-gateway',
            
            'admin-panel.index',
            'admin-panel.aside',
            
            'analytics.ga',
            'analytics.gtm',
            'analytics.fbpixel',

            'popup.index',
            'popup.alert',
            'popup.toast',
            'popup.confirm',
            
            'box.index',
            'box.row',
            
            'button.index',
            'button.create',
            'button.submit',
            'button.delete',
            
            'dropdown.index',
            'dropdown.item',
            
            'modal.index',
            'modal.row',
            
            'table.index',
            'table.th',
            'table.tr',
            'table.td',
            'table.checkbox',
            
            'sidenav.index',
            'sidenav.group',
            'sidenav.item',
            
            'form.index',
            'form.seo',
            'form.date',
            'form.file',
            'form.myic',
            'form.tags',
            'form.slug',
            'form.text',
            'form.agree',
            'form.email',
            'form.field',
            'form.image',
            'form.phone',
            'form.radio',
            'form.state',
            'form.title',
            'form.amount',
            'form.gender',
            'form.number',
            'form.picker',
            'form.search',
            'form.select',
            'form.country',
            'form.checkbox',
            'form.currency',
            'form.password',
            'form.richtext',
            'form.sortable',
            'form.textarea',
            'form.recaptcha',
            'form.date-range',

            'page-header.index',
            'page-header.back',
        
            'input.text',
            'input.email',
            'input.field',

            'tab.index',
            'tab.item',

            'blog.index',
            'blog.card',

            'navbar.index',
            'navbar.item',
            'navbar.login',
            'navbar.locale',
            'navbar.dropdown.index',
            'navbar.dropdown.item',
            'navbar.dropdown.auth',
            'navbar.mobile.index',
            'navbar.mobile.item',
            'navbar.mobile.locale',

            'slider.index',
            'slider.slide',
            'slider.nav',

            'faq.index',
            'faq.item',

            'footer.index',
            'footer.pre',

            'builder.hero',
            'builder.testimonial',
        ];

        foreach ($components as $value) {
            $name = collect(explode('.', $value))
                ->map(fn($str) => str()->studly($str))
                ->join('\\');

            if (str($name)->is('*\Index')) $value = str($value)->replace('.index', '')->toString();

            $classname = 'Jiannius\Atom\Components\\'.$name;

            Blade::component($value, $classname);
        }
    }
}