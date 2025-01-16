<?php

namespace Jiannius\Atom\Livewire;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class SiteSettings extends Component
{
    use AtomComponent;

    public $settings;

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
            'settings.facebook_url' => ['nullable'],
            'settings.instagram_url' => ['nullable'],
            'settings.twitter_url' => ['nullable'],
            'settings.linkedin_url' => ['nullable'],
            'settings.youtube_url' => ['nullable'],
            'settings.spotify_url' => ['nullable'],
            'settings.tiktok_url' => ['nullable'],
            'settings.documentation_url' => ['nullable'],
        ];
    }

    public function mount()
    {
        $this->settings = collect($this->validation())->keys()
            ->mapWithKeys(function($key) {
                $key = str($key)->replace('settings.', '')->toString();
                return [$key => settings($key)];
            })
            ->toArray();
    }

    public function submit() : void
    {
        $this->validate();

        settings($this->settings);
        
        Atom::toast('updated', 'success');
    }
}
