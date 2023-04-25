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
            'link',
            'logo',
            'plan',
            'alert',
            'badge',
            'field',
            'image',
            'avatar',
            'loader',
            'drawer',
            'spinner',
            'checkbox',
            'shareable',
            'thumbnail',
            'breadcrumbs',
            'cdn-scripts',
            'empty-state',
            'alpine-data',
            'social-share',
            'payment-gateway',
            'placeholder-bar',
            
            'dashboard.chart',
            'dashboard.listing',
            'dashboard.statbox',

            'landing.popup',
            'landing.announcement',
            
            'lightbox.index',
            'lightbox.slide',

            'close.index',
            'close.delete',

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
            'button.submit',
            'button.delete',
            'button.trashed',
            'button.confirm',
            'button.social-login',
            
            'dropdown.index',
            'dropdown.item',
            'dropdown.delete',
            
            'modal.index',
            'modal.row',
            
            'table.index',
            'table.th',
            'table.tr',
            'table.td',
            'table.export',
            'table.header',
            'table.filters',
            'table.toolbar',
            'table.trashed',
            'table.searchbar',
            'table.checkboxes',
            
            'sidenav.index',
            'sidenav.group',
            'sidenav.item',
            
            'form.index',
            'form.seo',
            'form.date',
            'form.tags',
            'form.myic',
            'form.tags',
            'form.slug',
            'form.text',
            'form.time',
            'form.agree',
            'form.color',
            'form.email',
            'form.field',
            'form.group',
            'form.image',
            'form.items',
            'form.phone',
            'form.radio',
            'form.title',
            'form.amount',
            'form.custom',
            'form.number',
            'form.picker',
            'form.search',
            'form.country',
            'form.password',
            'form.richtext',
            'form.sortable',
            'form.textarea',
            'form.recaptcha',
            'form.date-range',
            'form.checkbox-select',

            'form.file.index',
            'form.file.url',
            'form.file.library',
            'form.file.preview',
            'form.file.dropzone',

            'form.select.index',
            'form.select.label',
            'form.select.owner',
            'form.select.state',
            'form.select.gender',
            'form.select.contact',
            'form.select.country',
            'form.select.currency',

            'form.checkbox.index',
            'form.checkbox.privacy',
            'form.checkbox.multiple',            
            'form.checkbox.marketing',

            'page-header.index',
            'page-header.back',
        
            'tab.index',
            'tab.item',
            'tab.dropdown.index',
            'tab.dropdown.item',

            'blog.index',
            'blog.card',

            'navbar.index',
            'navbar.auth',
            'navbar.item',
            'navbar.locale',
            'navbar.dropdown.index',
            'navbar.dropdown.item',
            'navbar.mobile.index',
            'navbar.mobile.item',
            'navbar.mobile.locale',

            'slider.index',
            'slider.nav',
            'slider.slide',
            'slider.thumbs',

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