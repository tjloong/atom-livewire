<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Team;

use Livewire\Component;

class Create extends Component
{
    public $team;

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->team = model('team');

        breadcrumbs()->push('Create Team');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.team.create');
    }
}