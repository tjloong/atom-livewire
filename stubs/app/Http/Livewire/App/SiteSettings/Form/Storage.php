<?php

namespace App\Http\Livewire\App\SiteSettings\Form;

use Livewire\Component;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Validator;

class Storage extends Component
{
    public $settings;

    protected $rules = [
        'settings.filesystem' => 'required',
        'settings.do_spaces_key' => 'required_if:settings.filesystem,do',
        'settings.do_spaces_secret' => 'required_if:settings.filesystem,do',
        'settings.do_spaces_region' => 'required_if:settings.filesystem,do',
        'settings.do_spaces_bucket' => 'required_if:settings.filesystem,do',
        'settings.do_spaces_endpoint' => 'required_if:settings.filesystem,do',
        'settings.do_spaces_cdn' => 'required_if:settings.filesystem,do',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        SiteSetting::storage()->get()->each(function($setting) {
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
        return view('livewire.app.site-settings.form.storage');
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
                'settings.filesystem.required' => 'Storage provider is required',
                'settings.do_spaces_key.required_if' => 'DO spaces key is required.',
                'settings.do_spaces_secret.required_if' => 'DO spaces secret is required.',
                'settings.do_spaces_region.required_if' => 'DO spaces region is required.',
                'settings.do_spaces_bucket.required_if' => 'DO spaces bucket is required.',
                'settings.do_spaces_endpoint.required_if' => 'DO spaces endpoint is required.',
                'settings.do_spaces_cdn.required_if' => 'DO spaces CDN URL is required.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}