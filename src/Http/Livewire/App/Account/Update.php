<?php

namespace Jiannius\Atom\Http\Livewire\App\Account;

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
        $this->user->account->block();
        $this->dispatchBrowserEvent('toast', ['message' => 'Account Blocked']);
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->account->unblock();
        $this->dispatchBrowserEvent('toast', ['message' => 'Account Unblocked']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->user->delete();
        
        session()->flash('flash', 'Account Deleted');

        return redirect()->route('account.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update');
    }
}