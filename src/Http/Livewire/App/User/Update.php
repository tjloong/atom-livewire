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
    public function mount($id)
    {
        $this->user = model('user')->findOrFail($id);
        breadcrumb($this->user->name);
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
     * Render
     */
    public function render()
    {
        return view('atom::app.user.update');
    }
}