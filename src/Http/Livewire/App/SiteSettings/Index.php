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
            return redirect()->route('site-settings', [
                head($this->tabs['general'] ?? $this->tabs['system'])
            ]);
        }

        breadcrumbs()->flush();
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        $settings = model('site_setting');
        $tabs = [
            'general' => array_filter(array_merge(
                [
                    $settings->profile()->count() ? 'site-profile' : null,
                    $settings->tracking()->count() ? 'site-tracking' : null,
                    $settings->seo()->count() ? 'site-seo' : null,
                    $settings->social()->count() ? 'social-media' : null,
                    $settings->whatsapp()->count() ? 'whatsapp-bubble' : null,
                ], 
                config('atom.site-settings.settings', [])
            )),
            'system' => [
                'email-configurations',
                'storage',
            ],
        ];

        return array_filter($tabs);
    }

    /**
     * Get component name property
     */
    public function getComponentNameProperty()
    {
        $path = app_path('Http/Livewire/App/SiteSettings/Tabs/'.str()->studly($this->tab).'.php');

        return file_exists($path)
            ? 'app.site-settings.tabs.'.$this->tab
            : 'atom.site-settings.tabs.'.$this->tab;
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