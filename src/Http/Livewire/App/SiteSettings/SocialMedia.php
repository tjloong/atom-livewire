<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

class SocialMedia extends Component
{
    public $settings;
    public $platforms;

    /**
     * Mount
     */
    public function mount()
    {
        $this->platforms = [
            'facebook',
            'instagram',
            'twitter',
            'linkedin',
            'youtube',
            'spotify',
            'tiktok',
        ];

        SiteSetting::social()->get()->each(function($setting) {
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

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.social-media');
    }
}