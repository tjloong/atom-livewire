<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AtomComponentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $components = [
            'seo',
            'icon',
            'link',
            'logo',
            'plan',
            'badge',
            'field',
            'image',
            'avatar',
            'loader',
            'drawer',
            'spinner',
            'checkbox',
            'thumbnail',
            'plan-alert',
            'breadcrumbs',
            'cdn-scripts',
            'empty-state',
            'alpine-data',
            'page-overlay',
            'social-share',
            'flip-countdown',
            'payment-gateway',
            'placeholder-bar',
            'email-verification',

            'sortable.index',
            'sortable.item',
            
            'alert.index',
            'alert.errors',
            
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
            
            'analytics.fathom',
            'analytics.fbpixel',
            'analytics.ga',
            'analytics.gtm',

            'popup.index',
            'popup.alert',
            'popup.toast',
            'popup.confirm',
            
            'box.index',
            'box.fieldset',
            'box.trace',
            'box.row',
            
            'button.index',
            'button.group',
            'button.trash',
            'button.submit',
            'button.delete',
            'button.restore',
            'button.confirm',
            'button.social-login',
            
            'dropdown.index',
            'dropdown.item',
            'dropdown.trash',
            'dropdown.delete',
            'dropdown.restore',
            
            'modal.index',
            'modal.row',
            'modal.overlay',
            
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
            'form.qty',
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
            'form.datetime',
            'form.password',
            'form.richtext',
            'form.sortable',
            'form.textarea',
            'form.recaptcha',
            'form.date-range',
            'form.checkbox-select',

            'form.file.index',
            'form.file.url',
            'form.file.dropzone',
            'form.file.uploader',
            'form.file.library',
            'form.file.preview',

            'form.select.index',
            'form.select.enum',
            'form.select.role',
            'form.select.team',
            'form.select.label',
            'form.select.state',
            'form.select.gender',
            'form.select.country',
            'form.select.currency',

            'form.checkbox.index',
            'form.checkbox.privacy',
            'form.checkbox.multiple',            
            'form.checkbox.marketing',

            'document.index',
            'document.item',
            'document.item-header',
            'document.totals',

            'page-header.index',
            'page-header.back',
        
            'tabs.index',
            'tabs.tab',
            'tabs.dropdown.index',
            'tabs.dropdown.item',

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