<?php

namespace Jiannius\Atom\Http\Livewire\App\Team\Update;

use Livewire\Component;

class Users extends Component
{
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
        if (!$this->search) return [];

        return model('user')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->where('account_id', auth()->user()->account_id)
            ->where('id', '<>', auth()->id())
            ->whereDoesntHave('teams', fn($q) => $q->where('teams.id', $this->team->id))
            ->orderBy('name')
            ->take(50)
            ->get();
    }

    /**
     * Join team
     */
    public function join($id)
    {
        $user = $this->options->firstWhere('id', $id);

        if (!$user->teams()->find($this->team->id)) $user->teams()->attach($this->team->id);

        $this->dispatchBrowserEvent('toast', [
            'message' => 'Added team user.',
            'type' => 'success',
        ]);
    }

    /**
     * Leave team
     */
    public function leave($id)
    {
        $this->team->users()->detach($id);

        $this->dispatchBrowserEvent('toast', [
            'message' => 'Remove team user.',
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.update.users');
    }
}