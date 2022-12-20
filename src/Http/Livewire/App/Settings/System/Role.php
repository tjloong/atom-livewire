<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Role extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => null];

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get roles property
     */
    public function getRolesProperty()
    {
        return model('role')
            ->when(
                model('role')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->withCount('users')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Open
     */
    public function open($action, $data = null)
    {
        $component = [
            'create' => lw('app.settings.system.role-form-modal'),
            'edit' => lw('app.settings.system.role-form-modal'),
            'permission' => lw('app.settings.system.permission-form-modal'),
            'user' => lw('app.settings.system.user-drawer'),
        ][$action];


        $this->emitTo($component, 'open', $data);
    }

    /**
     * Duplicate
     */
    public function duplicate($id)
    {
        $role = model('role')->findOrFail($id);

        $newrole = model('role');
        $newrole->name = $role->name.' Copy';
        $newrole->save();

        if (enabled_module('permissions')) {
            $role->permissions->each(fn($permission) => $newrole->permissions()->create([
                'permission' => $permission->permission,
                'is_granted' => $permission->is_granted,
            ]));
        }

        $this->emitSelf('refresh');
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $role = model('role')->findOrFail($id);

        if ($role->users()->count() > 0) {
            $this->popup([
                'title' => 'Unable to Delete', 
                'message' => 'This role has users assigned to it.', 
            ], 'alert', 'error');
        }
        else {
            $role->delete();
            $this->popup('Role Deleted');
            $this->emitSelf('refresh');
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.system.role');
    }
}