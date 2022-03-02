<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $role;

    protected $messages = [
        'role.name.required' => 'Role name is required.',
        'role.name.unique' => 'There is another role with the same name.',
    ];

    protected function rules()
    {
        return [
            'role.name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->role),
            ],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Get users count property
     */
    public function getUsersCountProperty()
    {
        return $this->role->users()->count();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->role->save();
        $this->emitUp('saved', $this->role->id);
    }

    /**
     * Duplicate
     */
    public function duplicate()
    {
        $newrole = model('role');
        $newrole->name = $this->role->name . ' Copy';
        $newrole->save();

        $this->role->permissions->each(fn($permission) => $newrole->permissions()->create([
            'permission' => $permission->permission,
            'is_granted' => $permission->is_granted,
        ]));

        session()->flash('flash', 'Role Duplicated::success');

        return redirect()->route('role.update', [$newrole->id]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.role.form', [
            'count' => $this->users_count,
        ]);
    }
}