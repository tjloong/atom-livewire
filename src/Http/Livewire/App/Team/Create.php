<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;
use Jiannius\Atom\Models\Team;

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
        return view('atom::app.team.create');
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