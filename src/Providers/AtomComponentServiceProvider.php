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
            'box',
            'tabs',
            'card',
            'icon',
            'logo',
            'alert',
            'badge',
            'image',
            'modal',
            'avatar',
            'loader',
            'notify',
            'drawer',
            'stat-box',
            'checkbox',
            'file-card',
            'back-button',
            'empty-state',
            'alpine-data',
            'page-header',
            'breadcrumbs',
            'payment-gateway',
            
            'admin-panel.index',
            'admin-panel.aside',
            
            'analytics.ga',
            'analytics.gtm',
            'analytics.fbpixel',
            
            'button.index',
            'button.create',
            'button.submit',
            'button.delete',

            'dropdown.index',
            'dropdown.item',
            
            'table.index',
            'table.th',
            'table.tr',
            'table.td',

            'sidenav.index',
            'sidenav.group',
            'sidenav.item',

            'form.index',
            'form.ic',
            'form.seo',
            'form.date',
            'form.file',
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
            'form.errors',
            'form.gender',
            'form.number',
            'form.picker',
            'form.select',
            'form.country',
            'form.checkbox',
            'form.currency',
            'form.password',
            'form.richtext',
            'form.sortable',
            'form.textarea',
        
            'input.text',
            'input.email',
            'input.field',
            'input.search',
        
            'builder.faq',
            'builder.hero',
            'builder.share',
            'builder.footer',
            'builder.slider',
            'builder.navbar',
            'builder.pricing',
            'builder.testimonial',
            'builder.announcements',
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