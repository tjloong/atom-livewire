<?php

namespace App\Http\Livewire\App\Banner;

use App\Models\Banner;
use Livewire\Component;

class Create extends Component
{
    public $back;
    public $banner;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->back = request()->query('back');
        $this->banner = new Banner([
            'is_active' => true,
        ]);
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.banner.create');
    }

    /**
     * Handler after save
     * 
     * @return void
     */
    public function saved($id)
    {
        session()->flash('flash', 'Banner Created::success');
        return redirect()->route('banner.update', [$id, 'back' => $this->back]);
    }
}