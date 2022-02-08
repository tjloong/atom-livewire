<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\Form;

use Livewire\Component;
use Jiannius\Atom\Models\SiteSetting;

class Gmap extends Component
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
        $this->settings['gmap_api'] = SiteSetting::getSetting('gmap_api');
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.site-settings.form.gmap');
    }

    /**
     * Save settings
     * 
     * @return void
     */
    public function save()
    {
        SiteSetting::where('name', 'gmap_api')->update(['value' => $this->settings['gmap_api']]);

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }
}