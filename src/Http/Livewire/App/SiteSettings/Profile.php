<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Profile extends Component
{
    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->group('profile')->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->emitUp('submit', $this->settings);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.profile');
    }
}