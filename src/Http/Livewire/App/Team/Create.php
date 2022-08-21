<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;

class Create extends Component
{
    public $team;

    /**
     * Mount
     */
    public function mount()
    {
        $this->team = model('team');

        breadcrumbs()->push('Create Team');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.create');
    }
}