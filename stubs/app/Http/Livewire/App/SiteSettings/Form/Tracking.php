<?php

namespace App\Http\Livewire\App\SiteSettings\Form;

use App\Models\SiteSetting;
use Livewire\Component;

class Tracking extends Component
{
    public $settings;

    protected $rules = [];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        SiteSetting::tracking()->get()->each(function($setting) {
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
        return view('livewire.app.site-settings.form.tracking');
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