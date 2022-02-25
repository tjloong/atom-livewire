<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;
use Jiannius\Atom\Models\Role;

class Create extends Component
{
    public $role;

    protected $rules = [
        'role.name' => 'required|unique:roles,name',
        'role.scope' => 'required',
    ];

    protected $messages = [
        'role.name.required' => 'Role name is required.',
        'role.name.unique' => 'There is another role with the same name.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumb('Create Role');

        $this->role = new Role([
            'name' => null,
            'scope' => 'restrict',
        ]);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->role->save();

        return redirect()->route('role.update', [$this->role]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.role.create');
    }
}