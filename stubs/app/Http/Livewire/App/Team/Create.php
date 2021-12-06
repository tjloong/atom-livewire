<?php

namespace App\Http\Livewire\App\Team;

use App\Models\Team;
use Livewire\Component;

class Create extends Component
{
    public $team;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->team = new Team();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.team.create');
    }

    /**
     * Saved action
     * 
     * @return void
     */
    public function saved()
    {
        session()->flash('flash', 'Team Created::success');
        return redirect()->route('team.listing');
    }
}