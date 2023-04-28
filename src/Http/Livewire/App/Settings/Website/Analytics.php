<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Website;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Analytics extends Component
{
    use WithPopupNotify;

    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        $this->settings = settings('analytics');
    }

    /**
     * Submit
     */
    public function submit()
    {
        settings(['analytics' => $this->settings]);
        
        $this->popup('Website Analytics Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.website.analytics');
    }
}