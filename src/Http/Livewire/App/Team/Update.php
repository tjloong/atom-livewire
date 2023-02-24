<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;

class Update extends Component
{
    public $team;

    /**
     * Mount
     */
    public function mount($teamId)
    {
        $this->team = model('team')->readable()->findOrFail($teamId);

        breadcrumbs()->push($this->team->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->team->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.team.update');
    }
}