<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

class WhatsappBubble extends Component
{
    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        SiteSetting::whatsapp()->get()->each(function($setting) {
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
        foreach ($this->settings as $key => $value) {
            SiteSetting::where('name', $key)->update(['value' => $value]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => 'Whatsapp Bubble Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.whatsapp-bubble');
    }
}