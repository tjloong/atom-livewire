<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class SiteProfile extends Component
{
    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->profile()->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        site_settings($this->settings);

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Profile Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.site-profile');
    }
}