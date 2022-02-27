<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;

class Update extends Component
{
    public $role;
    public $search;

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->role = model('role')->findOrFail($id);
        breadcrumb($this->role->name);
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return model('user')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->where('role_id', $this->role->id)
            ->orderBy('name');
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
            session()->flash('flash', 'Role deleted');
            return redirect()->route('role.listing');
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.role.update', [
            'users' => $this->users->get(),
        ]);
    }
}