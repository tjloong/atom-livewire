<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;

class Update extends Component
{
    public $team;
    public $search;
    public $selectedUserId;

    protected $listeners = [
        'saved', 
        'removeUser',
    ];

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->team = model('team')->findOrFail($id);
        breadcrumbs()->push($this->team->name);
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return model('user')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->teamId($this->team->id);
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Team Updated', 'type' => 'success']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->team->delete();
        
        session()->flash('flash', 'Team Deleted');

        return redirect()->route('team.listing');
    }

    /**
     * Get users for picker
     */
    public function getUsersForPicker($page, $text = null)
    {
        return model('user')
            ->when($text, fn($q) => $q->search($text))
            ->when(enabled_module('signups'), fn($q) => $q->doesntHave('signup'))
            ->whereDoesntHave('teams', fn($q) => $q->where('teams.id', $this->team->id))
            ->paginate(30, ['*'], 'page', $page)
            ->toArray();
    }

    /**
     * Join team
     */
    public function join($id)
    {
        $user = model('user')->find($id);

        if (!$user->teams()->find($this->team->id)) $user->teams()->attach($this->team->id);

        $this->dispatchBrowserEvent('toast', ['message' => 'User joined team', 'type' => 'success']);
    }

    /**
     * Leave team
     */
    public function leave($id)
    {
        $user = model('user')->find($id);
        $user->teams()->detach($this->team->id);

        $this->dispatchBrowserEvent('toast', ['message' => 'User leaved team']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.update', [
            'users' => $this->users->get(),
        ]);
    }
}