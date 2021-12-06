<?php

namespace App\Http\Livewire\App\User;

use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $user;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->user = new User();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.user.create');
    }

    /**
     * Saved action
     * 
     * @return void
     */
    public function saved()
    {
        session()->flash('flash', 'User Created::success');
        return redirect()->route('user.listing');
    }
}