<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Update extends Component
{
    public $category;

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($category = null)
    {
        if (!$category) {
            if (config('atom.features.site_settings') === 'cms') $category = 'contact';
            else $category = 'email';

            return redirect()->route('site-settings', [$category]);
        }
        else $this->category = $category;
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.site-settings.update');
    }
}