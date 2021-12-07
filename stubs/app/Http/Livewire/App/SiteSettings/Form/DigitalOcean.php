<?php

namespace App\Http\Livewire\App\SiteSettings\Form;

use App\Models\SiteSetting;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class DigitalOcean extends Component
{
    public $settings;

    protected $rules = [
        'settings.do_spaces_key' => 'nullable',
        'settings.do_spaces_secret' => 'required_with:settings.do_spaces_key',
        'settings.do_spaces_region' => 'required_with:settings.do_spaces_key',
        'settings.do_spaces_bucket' => 'required_with:settings.do_spaces_key',
        'settings.do_spaces_endpoint' => 'required_with:settings.do_spaces_key',
        'settings.do_spaces_cdn' => 'required_with:settings.do_spaces_key',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        SiteSetting::do()->get()->each(function($setting) {
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
        return view('livewire.app.site-settings.form.digital-ocean');
    }

    /**
     * Save settings
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();

        foreach ($this->settings as $key => $value) {
            SiteSetting::where('name', $key)->update(['value' => $value]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    public function validateinputs()
    {
        $this->resetValidation();

        $validator = Validator::make(
            ['settings' => $this->settings],
            $this->rules,
            [
                'settings.do_spaces_secret.required_with' => 'DO spaces secret is required.',
                'settings.do_spaces_region.required_with' => 'DO spaces region is required.',
                'settings.do_spaces_bucket.required_with' => 'DO spaces bucket is required.',
                'settings.do_spaces_endpoint.required_with' => 'DO spaces endpoint is required.',
                'settings.do_spaces_cdn.required_with' => 'DO spaces CDN URL is required.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}