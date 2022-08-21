<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;

class Create extends Component
{
    public $user;

    /**
     * Mount
     */
    public function mount()
    {
        $this->user = model('user')->fill([
            'account_id' => auth()->user()->account_id,
        ]);

        breadcrumbs()->push('Create User');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.create');
    }
}