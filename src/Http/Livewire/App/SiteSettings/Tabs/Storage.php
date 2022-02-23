<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\Tabs;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;
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

    protected $messages = [
        'settings.filesystem.required' => 'Storage provider is required',
        'settings.do_spaces_key.required_if' => 'DO spaces key is required.',
        'settings.do_spaces_secret.required_if' => 'DO spaces secret is required.',
        'settings.do_spaces_region.required_if' => 'DO spaces region is required.',
        'settings.do_spaces_bucket.required_if' => 'DO spaces bucket is required.',
        'settings.do_spaces_endpoint.required_if' => 'DO spaces endpoint is required.',
        'settings.do_spaces_cdn.required_if' => 'DO spaces CDN URL is required.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        SiteSetting::storage()->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        foreach ($this->settings as $key => $value) {
            SiteSetting::where('name', $key)->update(['value' => $value]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.tabs.storage');
    }
}