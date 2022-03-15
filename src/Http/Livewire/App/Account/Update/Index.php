<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $tabs;
    public $user;

    /**
     * Mount
     */
    public function mount($id, $tab = null)
    {
        $this->user = model('user')->findOrFail($id);
        $this->tabs = config('atom.accounts.sidenavs');

        if (!$tab) return redirect()->route('app.account.update', [$id, head(array_keys($this->tabs))]);
        else $this->tab = $tab;

        breadcrumbs()->push($this->user->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->user->delete();
        
        session()->flash('flash', 'Account Deleted');

        return redirect()->route('app.account.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.index');
    }
}