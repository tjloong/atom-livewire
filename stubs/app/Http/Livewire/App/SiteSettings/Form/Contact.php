<?php

namespace App\Http\Livewire\App\SiteSettings\Form;

use App\Models\SiteSetting;
use Livewire\Component;

class Contact extends Component
{
    public $settings;

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        SiteSetting::contact()->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });

        SiteSetting::whatsapp()->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->name === 'whatsapp_bubble'
                ? (bool)$setting->value
                : $setting->value;
        });
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.site-settings.form.contact');
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