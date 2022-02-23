<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\Tabs;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

class GoogleMap extends Component
{
    public $settings;

    protected $rules = [];

    /**
     * Mount
     */
    public function mount()
    {
        $this->settings['gmap_api'] = SiteSetting::getSetting('gmap_api');
    }

    /**
     * Submit
     */
    public function submit()
    {
        SiteSetting::where('name', 'gmap_api')->update(['value' => $this->settings['gmap_api']]);

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.tabs.google-map');
    }
}