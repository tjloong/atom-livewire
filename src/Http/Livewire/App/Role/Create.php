<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;

class Create extends Component
{
    public $role;

    /**
     * Mount
     */
    public function mount()
    {
        $this->role = $this->duplicate() ?? model('role');

        breadcrumbs()->push('Create Role');
    }

    /**
     * Duplidate
     */
    public function duplicate()
    {
        if (!request()->query('duplicate_from_id')) return;

        $role = model('role')->findOrFail(request()->query('duplicate_from_id'));
        $newrole = model('role')->fill(['name' => $role->name.' Copy'])->save();

        if (enabled_module('permissions')) {
            $role->permissions->each(fn($permission) => $newrole->permissions()->create([
                'permission' => $permission->permission,
                'is_granted' => $permission->is_granted,
            ]));
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.role.create');
    }
}