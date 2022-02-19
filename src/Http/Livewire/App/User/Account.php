<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;

class Account extends Component
{
    protected $listeners = ['saved'];

    /**
     * Mount method
     * 
     * @return void
     */
    public function mount()
    {
        breadcrumb_home('My Account');
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.user.account');
    }

    /**
     * Saved handler
     * 
     * @return void
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Account Updated', 'type' => 'success']);
    }
}