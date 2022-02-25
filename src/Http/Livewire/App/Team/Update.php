<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;
use App\Models\User;
use Jiannius\Atom\Models\Team;

class Update extends Component
{
    public Team $team;
    public $search;
    public $selectedUserId;

    protected $listeners = [
        'saved', 
        'removeUser',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumb($this->team->name);
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return User::query()
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
     * Get assignable users
     */
    public function getAssignableUsers($page, $text = null)
    {
        return User::query()
            ->when($text, fn($q) => $q->search($text))
            ->paginate(30, ['*'], 'page', $page)
            ->toArray();
    }

    /**
     * Assign user to team
     */
    public function assignUser($id)
    {
        $user = User::find($id);
        $user->joinTeam($this->team->id);
        $this->dispatchBrowserEvent('toast', ['message' => 'User joined team', 'type' => 'success']);
    }

    /**
     * Remove user from team
     */
    public function removeUser($id)
    {
        $user = User::find($id);
        $user->leaveTeam($this->team->id);
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