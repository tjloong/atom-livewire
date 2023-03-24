<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;

class Create extends Component
{
    public $role;

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->role = model('role');

        breadcrumbs()->push('Create Role');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.role.create');
    }
}