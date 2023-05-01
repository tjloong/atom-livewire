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
            'visibility' => 'restrict',
            'is_root' => tier('root'),
        ]);

        breadcrumbs()->push('Create User');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.user.create');
    }
}