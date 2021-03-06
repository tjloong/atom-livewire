<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;

class Create extends Component
{
    public $team;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->push('Create Team');
        $this->team = model('team');
    }

    /**
     * Saved
     */
    public function saved()
    {
        session()->flash('flash', 'Team Created::success');
        return redirect()->route('app.team.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.create');
    }
}