<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Index extends Component
{
    public $tab;

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->tab) {
            return redirect()->route('app.site-settings', [$this->tabs->first()->tabs->first()->slug]);
        }

        breadcrumbs()->flush();
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        $settings = model('site_setting');

        $tabs = collect(json_decode(json_encode(
            [
                ['group' => 'General', 'tabs' => array_filter([
                    $settings->profile()->count() ? ['slug' => 'site-profile'] : null,
                    $settings->tracking()->count() ? ['slug' => 'site-tracking'] : null,
                    $settings->seo()->count() ? ['slug' => 'site-seo'] : null,
                    $settings->social()->count() ? ['slug' => 'social-media'] : null,
                    $settings->whatsapp()->count() ? ['slug' => 'whatsapp-bubble'] : null,
                ])],
                ['group' => 'System', 'tabs' => array_filter([
                    ['slug' => 'email-configurations'],
                    ['slug' => 'storage'],
                    in_array('ozopay', config('atom.payment_gateway', [])) ? ['slug' => 'ozopay'] : null,
                ])],
            ]
        )))
            ->map(function($val) {
                $val->tabs = collect($val->tabs);
                return $val;
            })
            ->filter(fn($val) => $val->tabs->count());

        return $tabs;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.index', [
            'tabs' => $this->tabs,
        ]);
    }
}