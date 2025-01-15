<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Livewire\Component;
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
            'settings.contact_name' => ['nullable'],
            'settings.contact_phone' => ['nullable'],
            'settings.contact_email' => ['nullable'],
            'settings.contact_address' => ['nullable'],
            'settings.contact_map' => ['nullable'],
            'settings.whatsapp_bubble' => ['nullable'],
            'settings.whatsapp_number' => ['nullable'],
            'settings.whatsapp_text' => ['nullable'],
            'settings.meta_title' => ['nullable'],
            'settings.meta_description' => ['nullable'],
            'settings.meta_image' => ['nullable'],
            'settings.ga_id' => ['nullable'],
            'settings.gtm_id' => ['nullable'],
            'settings.fbpixel_id' => ['nullable'],
            'settings.recaptcha_site_key' => ['nullable'],
            'settings.recaptcha_secret_key' => ['nullable'],
            'settings.facebook_url' => ['nullable'],
            'settings.instagram_url' => ['nullable'],
            'settings.twitter_url' => ['nullable'],
            'settings.linkedin_url' => ['nullable'],
            'settings.youtube_url' => ['nullable'],
            'settings.spotify_url' => ['nullable'],
            'settings.tiktok_url' => ['nullable'],
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