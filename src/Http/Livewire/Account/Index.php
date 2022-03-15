<?php

namespace Jiannius\Atom\Http\Livewire\Account;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $tabs;
    public $user;

    /**
     * Mount
     */
    public function mount($tab = null)
    {
        $this->user = auth()->user();
        $this->tabs = get_tabs_from_config('atom.accounts.sidenavs');

        if (!$tab) return redirect()->route('account', [$this->tabs->first()['value']]);
        else $this->tab = $tab;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::account.index')->layout('layouts.account');
    }
}