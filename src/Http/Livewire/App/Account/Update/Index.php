<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'overview';
    public $account;

    /**
     * Mount
     */
    public function mount($account)
    {
        $this->account = model('account')->findOrFail($account);

        breadcrumbs()->push($this->account->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return collect(['overview']);
    }

    /**
     * Block
     */
    public function block()
    {
        $this->account->block();

        return redirect($this->redirectTo());
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->account->unblock();

        return redirect($this->redirectTo());
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->account->delete();
        
        session()->flash('flash', 'Account Deleted');

        return redirect($this->redirectTo());
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return route('app.account.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.index');
    }
}