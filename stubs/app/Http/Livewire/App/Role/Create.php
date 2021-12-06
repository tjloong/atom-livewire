<?php

namespace App\Http\Livewire\App\Role;

use App\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

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
        return view('livewire.app.role.create');
    }

    /**
     * Save role
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();
        $this->role->save();
        return redirect()->route('role.update', [$this->role, 'back' => $this->back]);
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    public function validateinputs()
    {
        $this->resetValidation();

        $validator = Validator::make(
            ['role' => $this->role],
            $this->rules,
            [
                'role.name.required' => 'Role name is required.',
                'role.name.unique' => 'There is another role with the same name.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}