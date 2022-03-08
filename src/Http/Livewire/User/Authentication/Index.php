<?php

namespace Jiannius\Atom\Http\Livewire\User\Authentication;

use Livewire\Component;

class Index extends Component
{
    public $user;

    /**
     * Mount
     */
    public function mount()
    {
        $this->user = auth()->user();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::user.authentication.index')->layout('layouts.user');
    }
}