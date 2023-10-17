<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;

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
            'loader',
            'drawer',
            'heading',
            'spinner',
            'checkbox',
            'no-result',
            'thumbnail',
            'breadcrumbs',
            'cdn-scripts',
            'alpine-data',
            'page-overlay',
            'social-share',
            'contact-card',
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
            'box.flat',
            'box.fieldset',
            'box.trace',
            'box.row',
            'box.stat',
            
            'button.index',
            'button.edit',
            'button.group',
            'button.trash',
            'button.submit',
            'button.delete',
            'button.restore',
            'button.archive',
            'button.confirm',
            'button.social-login',
            
            'dropdown.index',
            'dropdown.item',
            'dropdown.trash',
            'dropdown.delete',
            'dropdown.archive',
            'dropdown.restore',
            
            'modal.index',
            'modal.row',
            'modal.overlay',
            
            'table.index',
            'table.th',
            'table.tr',
            'table.td',
            'table.export',
            'table.heading',
            'table.filters',
            'table.toolbar',
            'table.trashed',
            'table.archived',
            'table.searchbar',
            'table.checkbox-actions',
            
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
            'form.drawer',
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
            'form.file.listing',

            'form.select.index',
            'form.select.enum',
            'form.select.role',
            'form.select.label',
            'form.select.state',
            'form.select.gender',
            'form.select.country',
            'form.select.currency',

            'form.checkbox.index',
            'form.checkbox.privacy',
            'form.checkbox.multiple',            
            'form.checkbox.marketing',

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

        ComponentAttributeBag::macro('hasLike', function() {
            $value = func_get_args();
            $keys = collect($this->getAttributes())->keys();

            return is_numeric(collect($value)->search(fn($val) => 
                $keys->search(fn($key) => str($key)->is($val))
            ));
        });
    }
}