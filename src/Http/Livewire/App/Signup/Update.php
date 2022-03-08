<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Livewire\Component;

class Update extends Component
{
    public $user;

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->user = model('user')->findOrFail($id);
        breadcrumbs()->push($this->user->name);
    }

    /**
     * Block
     */
    public function block()
    {
        $this->user->signup->block();
        $this->dispatchBrowserEvent('toast', ['message' => 'Sign-Up Blocked']);
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->signup->unblock();
        $this->dispatchBrowserEvent('toast', ['message' => 'Sign-Up Unblocked']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->user->delete();
        
        session()->flash('flash', 'Sign-Up Deleted');

        return redirect()->route('signup.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.signup.update');
    }
}