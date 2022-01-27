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
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.team.update', [
            'users' => User::query()
                ->when($this->search, fn($q) => $q->search($this->search))
                ->teamId($this->team->id)
                ->get(),
        ]);
    }

    /**
     * Saved action
     * 
     * @return void
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Team Updated', 'type' => 'success']);
    }

    /**
     * Delete team
     * 
     * @return void
     */
    public function delete()
    {
        $this->team->delete();
        
        session()->flash('flash', 'Team Deleted');

        return redirect()->route('team.listing');
    }

    /**
     * Get assignable users
     * 
     * @return User
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
     * 
     * @return void
     */
    public function assignUser($id)
    {
        $user = User::find($id);
        $user->joinTeam($this->team->id);
        $this->dispatchBrowserEvent('toast', ['message' => 'User joined team', 'type' => 'success']);
    }

    /**
     * Remove user from team
     * 
     * @return void
     */
    public function removeUser($id)
    {
        $user = User::find($id);
        $user->leaveTeam($this->team->id);
        $this->dispatchBrowserEvent('toast', ['message' => 'User leaved team']);
    }
}