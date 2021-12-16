<?php

namespace App\Http\Livewire\App\SiteSettings\Form;

use App\Models\SiteSetting;
use Livewire\Component;

class Contact extends Component
{
    public $settings;

    protected $rules = [
        'settings.company' => 'nullable',
        'settings.phone' => 'nullable',
        'settings.email' => 'nullable',
        'settings.whatsapp' => 'nullable',
        'settings.address' => 'nullable',
        'settings.facebook' => 'nullable',
        'settings.instagram' => 'nullable',
        'settings.twitter' => 'nullable',
        'settings.linkedin' => 'nullable',
    ];

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