<?php

namespace App\Http\Livewire\Web;

use App\Models\Banner;
use Livewire\Component;

class Home extends Component
{
    public $banners;

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->banners = Banner::status('active')->latest()->get();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.web.home')->layout('layouts.web');
    }
}