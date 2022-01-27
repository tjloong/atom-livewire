<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\Form;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

class SocialMedia extends Component
{
    public $settings;
    public $platforms;

    /**
     * Mount event
     * 
     * @return void
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
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.site-settings.form.social-media');
    }

    /**
     * Save settings
     * 
     * @return void
     */
    public function save()
    {
        foreach ($this->settings as $key => $value) {
            SiteSetting::where('name', $key)->update(['value' => $value]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }
}