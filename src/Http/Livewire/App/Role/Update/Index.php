<?php

namespace Jiannius\Atom\Http\Livewire\App\Role\Update;

use Jiannius\Atom\Traits\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use WithPopupNotify;

    public $tab = 'info';
    public $role;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($role)
    {
        $this->role = model('role')
            ->when(model('role')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
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
            $this->popup([
                'title' => 'Unable to Delete', 
                'message' => 'This role has users assigned to it.', 
            ], 'alert', 'error');
        }
        else {
            $this->role->delete();
            return redirect()->route('app.settings', ['roles'])->with('info', 'Role Deleted');
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