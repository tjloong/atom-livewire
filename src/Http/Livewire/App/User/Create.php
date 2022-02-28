<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;

class Create extends Component
{
    public $user;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->push('Create User');

        $this->user = model('user');
    }

    /**
     * Saved
     */
    public function saved()
    {
        session()->flash('flash', 'User Created::success');
        return redirect()->route('user.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.create');
    }
}