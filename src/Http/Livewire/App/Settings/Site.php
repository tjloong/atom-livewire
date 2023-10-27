<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Site extends Component
{
    use WithForm;

    public $settings;

    // validation
    protected function validation() : array
    {
        return [
            'settings.site_name' => ['required' => 'Site name is required.'],
            'settings.site_description' => ['nullable'],
            'settings.site_contact_name' => ['nullable'],
            'settings.site_contact_phone' => ['nullable'],
            'settings.site_contact_email' => ['nullable'],
            'settings.site_contact_address' => ['nullable'],
            'settings.site_contact_map' => ['nullable'],
            'settings.site_whatsapp_bubble' => ['nullable'],
            'settings.site_whatsapp_number' => ['nullable'],
            'settings.site_whatsapp_text' => ['nullable'],
            'settings.site_meta_title' => ['nullable'],
            'settings.site_meta_description' => ['nullable'],
            'settings.site_meta_image' => ['nullable'],
            'settings.site_ga_id' => ['nullable'],
            'settings.site_gtm_id' => ['nullable'],
            'settings.site_fbpixel_id' => ['nullable'],
            'settings.site_facebook_url' => ['nullable'],
            'settings.site_instagram_url' => ['nullable'],
            'settings.site_twitter_url' => ['nullable'],
            'settings.site_linkedin_url' => ['nullable'],
            'settings.site_youtube_url' => ['nullable'],
            'settings.site_spotify_url' => ['nullable'],
            'settings.site_tiktok_url' => ['nullable'],
        ];
    }

    // mount
    public function mount() : void
    {
        parent::mount();

        $this->settings = collect($this->validation())->keys()
            ->mapWithKeys(function($key) {
                $key = str($key)->replace('settings.', '')->toString();
                return [$key => settings($key)];
            })
            ->toArray();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        settings($this->settings);
        
        $this->popup('settings.alert.site-updated');
    }
}