<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;
use Jiannius\Atom\Models\Team;

class Create extends Component
{
    public $team;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumb('Create Team');
        $this->team = new Team();
    }

    /**
     * Saved
     */
    public function saved()
    {
        session()->flash('flash', 'Team Created::success');
        return redirect()->route('team.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.create');
    }
}