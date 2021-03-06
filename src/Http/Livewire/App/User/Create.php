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
        $this->user->account_id = auth()->user()->account_id;
    }

    /**
     * Saved
     */
    public function saved($id)
    {
        session()->flash('flash', 'User Created::success');
        return redirect()->route('app.user.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.create');
    }
}