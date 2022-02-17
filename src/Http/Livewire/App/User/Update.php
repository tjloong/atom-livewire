<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;
use App\Models\User;
use Jiannius\Atom\Models\Team;

class Update extends Component
{
    public User $user;

    protected $listeners = ['saved', 'leaveTeam'];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumb(['label' => $this->user->name]);
    }

    /**
     * Get teams property
     */
    public function getTeamsProperty()
    {
        if (!enabled_feature('teams')) return;

        return Team::userId($this->user->id)->get();
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'User Updated', 'type' => 'success']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->user->delete();
        session()->flash('flash', 'User deleted');
        return redirect()->route('user.listing');
    }

    /**
     * Get teams to join
     */
    public function getTeams($page, $text = null)
    {
        return Team::query()
            ->when($text, fn($q) => $q->search($text))
            ->orderBy('name')
            ->paginate(30, ['*'], 'page', $page)
            ->toArray();
    }

    /**
     * Join team
     */
    public function joinTeam($id)
    {
        $this->user->joinTeam($id);
        $this->dispatchBrowserEvent('toast', ['message' => 'User joined team', 'type' => 'success']);
    }

    /**
     * Leave team
     */
    public function leaveTeam($id)
    {
        $this->user->leaveTeam($id);
        $this->dispatchBrowserEvent('toast', ['message' => 'User leaved team']);
    }

    /**
     * Reset abilities
     */
    public function resetAbilities()
    {
        $this->user->abilities()->detach();
        $this->dispatchBrowserEvent('toast', ['message' => 'Permissions Updated']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.update', ['teams' => $this->teams]);
    }
}