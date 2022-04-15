<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Analytics extends Component
{
    public $settings;

    protected $rules = [];

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->group('analytics')->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->emitUp('submit', $this->settings);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.analytics');
    }
}