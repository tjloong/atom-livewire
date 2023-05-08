<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;

class Team extends Component
{
    public $user;

    /**
     * Get teams property
     */
    public function getTeamsProperty(): mixed
    {
        return model('team')->readable()->get();
    }

    /**
     * Toggle
     */
    public function toggle($id): void
    {
        $this->user->teams()->toggle($id);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.team');
    }
}