<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;

class Update extends Component
{
    public $user;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($user)
    {
        $this->user = model('user')->findOrFail($user);
        breadcrumbs()->push($this->user->name);
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'User Updated', 'type' => 'success']);
    }

    /**
     * Block
     */
    public function block()
    {
        $this->user->block();

        return redirect()->route('app.user.listing');
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->unblock();
        
        return redirect()->route('app.user.listing');
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->user->delete();

        session()->flash('flash', 'User deleted');

        return redirect()->route('app.user.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.update');
    }
}