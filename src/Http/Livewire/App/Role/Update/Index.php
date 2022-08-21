<?php

namespace Jiannius\Atom\Http\Livewire\App\Role\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'info';
    public $role;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($role)
    {
        $this->role = model('role')
            ->belongsToAccount()
            ->findOrFail($role);

        breadcrumbs()->push($this->role->name);
    }

    /**
     * Duplicate
     */
    public function duplicate()
    {
        $newrole = model('role');
        $newrole->name = $this->role->name . ' Copy';
        $newrole->save();

        if (enabled_module('permissions')) {
            $this->role->permissions->each(fn($permission) => $newrole->permissions()->create([
                'permission' => $permission->permission,
                'is_granted' => $permission->is_granted,
            ]));
        }

        return redirect()->route('app.role.update', [$newrole->id]);
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($this->role->users()->count() > 0) {
            $this->dispatchBrowserEvent('alert', [
                'title' => 'Unable to Delete', 
                'message' => 'This role has users assigned to it.', 
                'type' => 'error',
            ]);
        }
        else {
            $this->role->delete();
            session()->flash('flash', 'Role Deleted');
            return redirect()->route('app.role.listing');
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.role.update.index');
    }
}