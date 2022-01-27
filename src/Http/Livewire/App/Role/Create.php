<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;
use Jiannius\Atom\Models\Role;

class Create extends Component
{
    public $role;
    public $back;

    protected $rules = [
        'role.name' => 'required|unique:roles,name',
        'role.scope' => 'required',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->back = request()->query('back');
        $this->role = new Role([
            'name' => null,
            'scope' => 'restrict',
        ]);
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.role.create');
    }

    /**
     * Save role
     * 
     * @return void
     */
    public function save()
    {
        $this->validateInputs();
        $this->role->save();
        return redirect()->route('role.update', [$this->role, 'back' => $this->back]);
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    public function validateInputs()
    {
        $this->resetValidation();

        $validator = validator(['role' => $this->role], $this->rules, [
            'role.name.required' => 'Role name is required.',
            'role.name.unique' => 'There is another role with the same name.',
        ]);

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}