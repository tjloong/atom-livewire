<?php

namespace App\Http\Livewire\App\Banner;

use App\Models\Banner;
use Livewire\Component;

class Update extends Component
{
    public Banner $banner;

    protected $listeners = ['saved'];

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
        return view('livewire.app.banner.update');
    }

    /**
     * Handler after save
     * 
     * @return void
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Banner Updated', 'type' => 'success']);
    }
}