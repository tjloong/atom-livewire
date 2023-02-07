<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;

class Update extends Component
{
    public $role;

    /**
     * Mount
     */
    public function mount($roleId)
    {
        $this->role = model('role')->when(
            model('role')->enabledHasTenantTrait,
            fn($q) => $q->belongsToTenant(),
        )->findOrFail($roleId);

        breadcrumbs()->push($this->role->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($this->role->users()->count() > 0) {
            $this->popup([
                'title' => 'Unable to Delete', 
                'message' => 'This role has users assigned to it.', 
            ], 'alert', 'error');
        }
        else {
            $this->role->delete();
            return breadcrumbs()->back();
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.role.update');
    }
}