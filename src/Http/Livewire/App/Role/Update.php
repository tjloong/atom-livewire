<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $role;

    /**
     * Mount
     */
    public function mount($roleId): void
    {
        $this->role = model('role')->readable()->findOrFail($roleId);

        breadcrumbs()->push($this->role->name);
    }

    /**
     * Duplicate
     */
    public function duplicate(): mixed
    {
        $role = model('role')->create(['name' => $this->role->name.' Copy']);

        if (enabled_module('permissions')) {
            $this->role->permissions->each(fn($permission) => $role->permissions()->create([
                'permission' => $permission->permission,
                'is_granted' => $permission->is_granted,
            ]));
        }

        return redirect()->route('app.role.update', [$role->id]);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        if ($this->role->users()->count() > 0) $err = 'This role has users assigned to it.';
        if ($this->role->is_admin && model('role')->readable()->isAdmin()->count() <= 1) $err = 'You must have at least 1 admin role.';

        if (isset($err)) {
            return $this->popup(['title' => 'Unable to Delete', 'message' => $err], 'alert', 'error');
        }

        $this->role->delete();
        
        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.role.update');
    }
}