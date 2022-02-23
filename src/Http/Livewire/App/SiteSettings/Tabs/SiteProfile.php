<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\Tabs;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

class SiteProfile extends Component
{
    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        SiteSetting::profile()->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        foreach ($this->settings as $key => $value) {
            SiteSetting::where('name', $key)->update(['value' => $value]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Profile Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.tabs.site-profile');
    }
}