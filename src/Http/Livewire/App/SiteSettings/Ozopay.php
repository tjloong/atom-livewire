<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Ozopay extends Component
{
    public $settings;

    protected $rules = [
        'settings.ozopay_tid' => 'required',
        'settings.ozopay_secret' => 'required',
    ];

    protected $messages = [
        'settings.ozopay_tid.required' => 'Terminal ID is required.',
        'settings.ozopay_secret.required' => 'Secret is required.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->ozopay()->get()->each(function($setting) {
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

        site_settings($this->settings);

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.ozopay');
    }
}