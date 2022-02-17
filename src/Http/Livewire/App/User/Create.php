<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use App\Models\User;
use Jiannius\Atom\Models\Role;
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
        breadcrumb(['label' => 'Create User']);

        $this->user = new User([
            'role_id' => Role::where('slug', 'administrator')->where('is_system', true)->first()->id ?? null,
        ]);
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