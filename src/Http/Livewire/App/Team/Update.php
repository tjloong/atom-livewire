<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;

class Update extends Component
{
    public $team;

    /**
     * Mount
     */
    public function mount($teamId): void
    {
        $this->team = model('team')->readable()->findOrFail($teamId);

        breadcrumbs()->push($this->team->name);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->team->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.team.update');
    }
}