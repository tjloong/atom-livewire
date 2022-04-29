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
        $this->user = model('user')
            ->withTrashed()
            ->findOrFail($user);

        breadcrumbs()->push($this->user->name);
    }

    /**
     * Saved
     */
    public function saved($id)
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'User Updated', 'type' => 'success']);
    }

    /**
     * Block
     */
    public function block()
    {
        $this->user->block();

        return redirect($this->redirectTo());
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->unblock();
        
        return redirect($this->redirectTo());
    }

    /**
     * Delete
     */
    public function delete($force = false)
    {
        if ($force) $this->user->forceDelete();
        else $this->user->delete();

        session()->flash('flash', 'User deleted');

        return redirect($this->redirectTo());
    }

    /**
     * Restore
     */
    public function restore()
    {
        $this->user->restore();

        session()->flash('flash', 'User Restored');

        return redirect($this->redirectTo());
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return route('app.user.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.update');
    }
}