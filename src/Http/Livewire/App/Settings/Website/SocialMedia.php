<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Website;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class SocialMedia extends Component
{
    use WithPopupNotify;

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

        model('site_setting')->group('social')->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        site_settings($this->settings);
        $this->popup('Website Social Media Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.settings.website.social-media');
    }
}