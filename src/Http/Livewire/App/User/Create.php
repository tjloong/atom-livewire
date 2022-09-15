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
        $this->user = model('user');
        
        if ($accountId = request()->query('account')) {
            if (auth()->user()->isAccountType('root')) $this->user->fill(['account_id' => (integer)$accountId]);
        }
        else $this->user->fill(['account_id' => auth()->user()->account_id]);

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
        return view('atom::app.user.create');
    }
}