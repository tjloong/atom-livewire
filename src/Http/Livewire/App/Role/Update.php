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
    public function mount($roleId)
    {
        $this->role = model('role')->readable()->findOrFail($roleId);

        breadcrumbs()->push($this->role->name);
    }

    /**
     * Delete
     */
    public function delete()
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
    public function render()
    {
        return atom_view('app.role.update');
    }
}