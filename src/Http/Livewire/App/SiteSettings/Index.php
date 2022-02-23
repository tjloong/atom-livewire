<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

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
                head($this->tabs['general']) ?? head($this->tabs['system'])
            ]);
        }

        breadcrumb(false);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        $tabs = [
            'general' => array_filter([
                SiteSetting::profile()->count() ? 'site-profile' : null,
                SiteSetting::tracking()->count() ? 'site-tracking' : null,
                SiteSetting::seo()->count() ? 'site-seo' : null,
                SiteSetting::social()->count() ? 'social-media' : null,
                SiteSetting::whatsapp()->count() ? 'whatsapp-bubble' : null,
            ]),
            'system' => [
                'email-configurations',
                'storage',
                'google-map',
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