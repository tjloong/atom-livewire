<?php

namespace Jiannius\Atom\Http\Livewire\App\Team\Update;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Users extends Component
{
    use WithPopupNotify;

    public $team;
    public $search;

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return $this->team->users()->paginate(50);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return model('user')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->where('account_id', auth()->user()->account_id)
            ->where('id', '<>', auth()->id())
            ->whereDoesntHave('teams', fn($q) => $q->where('teams.id', $this->team->id))
            ->orderBy('name')
            ->get();
    }

    /**
     * Join team
     */
    public function join($id)
    {
        $user = $this->options->firstWhere('id', $id);

        if (!$user->teams()->find($this->team->id)) $user->teams()->attach($this->team->id);

        $this->popup('Added Team User.');
    }

    /**
     * Leave team
     */
    public function leave($id)
    {
        $this->team->users()->detach($id);
        $this->popup('Removed Team User.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.team.update.users');
    }
}