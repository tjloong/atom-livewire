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

        if (request()->query('account') && auth()->user()->isAccountType('root')) {
            $this->user->fill([
                'account_id' => (integer)request()->query('account'),
            ]);
        }

        if ($roleId = request()->query('role')) {
            $this->user->fill(['role_id' => (integer)$roleId]);
        }

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