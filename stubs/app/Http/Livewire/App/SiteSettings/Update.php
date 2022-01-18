<?php

namespace App\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Update extends Component
{
    public $category;

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.site-settings.update');
    }
}