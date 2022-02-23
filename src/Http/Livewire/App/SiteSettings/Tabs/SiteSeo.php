<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\Tabs;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

class SiteSeo extends Component
{
    public $settings;

    protected $rules = [
        'settings.seo_title' => 'nullable',
        'settings.seo_description' => 'nullable',
        'settings.seo_image' => 'nullable',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        SiteSetting::seo()->get()->each(function($setting) {
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
        return view('atom::app.site-settings.tabs.site-seo');
    }
}