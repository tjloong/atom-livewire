<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Traits\WithPopupNotify;
use Livewire\Component;

class Storage extends Component
{
    use WithPopupNotify;
    
    public $settings;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'settings.filesystem' => 'required',
            'settings.do_spaces_key' => 'required_if:settings.filesystem,do',
            'settings.do_spaces_secret' => 'required_if:settings.filesystem,do',
            'settings.do_spaces_region' => 'required_if:settings.filesystem,do',
            'settings.do_spaces_bucket' => 'required_if:settings.filesystem,do',
            'settings.do_spaces_endpoint' => 'required_if:settings.filesystem,do',
            'settings.do_spaces_folder' => 'required_if:settings.filesystem,do',
        ];
    }

    protected function messages()
    {
        return [
            'settings.filesystem.required' => __('Storage provider is required'),
            'settings.do_spaces_key.required_if' => __('DO spaces key is required.'),
            'settings.do_spaces_secret.required_if' => __('DO spaces secret is required.'),
            'settings.do_spaces_region.required_if' => __('DO spaces region is required.'),
            'settings.do_spaces_bucket.required_if' => __('DO spaces bucket is required.'),
            'settings.do_spaces_endpoint.required_if' => __('DO spaces endpoint is required.'),
            'settings.do_spaces_folder.required_if' => __('DO spaces folder is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->settings['filesystem'] = site_settings('filesystem');

        model('site_setting')->group('do')
            ->get()
            ->each(fn($setting) => $this->settings[$setting->name] = $setting->value);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        site_settings($this->settings);

        $this->popup('Storage Integration Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.settings.integration.storage');
    }
}