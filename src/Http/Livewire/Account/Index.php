<?php

namespace Jiannius\Atom\Http\Livewire\Account;

use Livewire\Component;

class Index extends Component
{
    public $tab;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->tab) $this->tab = 'authentication';
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return collect(['authentication']);
    }

    /**
     * Render
     */
    public function render()
    {
        $view = view('atom::account.index');
        
        return current_route('app.account') ? $view : $view->layout('layouts.account');
    }
}