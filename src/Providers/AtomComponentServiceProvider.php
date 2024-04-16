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
            'icon',
            'link',
            'logo',
            'plan',
            'badge',
            'field',
            'group',
            'image',
            'label',
            'modal',
            'popup',
            'anchor',
            'drawer',
            'inline',
            'loader',
            'slider',
            'divider',
            'heading',
            'spinner',
            'checkbox',
            'lightbox',
            'no-result',
            'file-card',
            'html-meta',
            'thumbnail',
            'breadcrumbs',
            'cdn-scripts',
            'alpine-data',
            'announcement',
            'media-object',
            'page-overlay',
            'social-share',
            'contact-card',
            'flip-countdown',
            'payment-gateway',
            'whatsapp-bubble',
            'placeholder-bar',
            'email-verification',

            'sortable.index',
            'sortable.item',
            
            'alert.index',
            'alert.errors',
            
            'dashboard.chart',
            'dashboard.listing',
            'dashboard.statbox',

            'close.index',
            'close.delete',

            'admin-panel.index',
            'admin-panel.aside',
            
            'analytics.fathom',
            'analytics.fbpixel',
            'analytics.ga',
            'analytics.gtm',

            'notify.alert',
            'notify.toast',
            'notify.confirm',
            
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
            'dropdown.footprint',
            
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
            'form.tags',
            'form.myic',
            'form.tags',
            'form.slug',
            'form.text',
            'form.agree',
            'form.color',
            'form.email',
            'form.field',
            'form.image',
            'form.items',
            'form.phone',
            'form.radio',
            'form.amount',
            'form.custom',
            'form.drawer',
            'form.number',
            'form.search',
            'form.country',
            'form.password',
            'form.richtext',
            'form.sortable',
            'form.textarea',
            'form.signature',
            'form.recaptcha',
            'form.permission',
            'form.checkbox-select',
            
            'form.editor.index',
            'form.editor.text',
            'form.editor.media',
            'form.editor.tools',
            'form.editor.table',
            'form.editor.bullet',
            'form.editor.actions',
            'form.editor.heading',
            'form.editor.dropdown',
            
            'form.file.index',
            'form.file.url',
            'form.file.dropzone',
            'form.file.uploader',
            'form.file.listing',
            
            'form.select.index',
            'form.select.enum',
            'form.select.email',
            'form.select.label',
            'form.select.state',
            'form.select.gender',
            'form.select.country',
            'form.select.currency',
            'form.select.nationality',
            
            'form.checkbox.index',
            'form.checkbox.privacy',
            'form.checkbox.multiple',            
            'form.checkbox.marketing',

            'form.date.index',
            'form.date.picker',
            'form.date.time',

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

            return !empty(
                $keys->first(fn($key) => str($key)->is($value))
            );
        });
    }
}