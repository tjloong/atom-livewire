<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Website;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Profile extends Component
{
    use WithPopupNotify;
    
    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        model('setting')->group('profile')->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });

        model('setting')->group('whatsapp')->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->name === 'whatsapp_bubble'
                ? (bool)$setting->value
                : $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        settings($this->settings);
        $this->popup('Website Profile Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.website.profile');
    }
}