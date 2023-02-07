<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithPopupNotify;

    public $role;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'role.name' => [
                'required',
                Rule::unique('roles', 'name')
                    ->when(
                        model('role')->enabledHasTenantTrait,
                        fn($q) => $q->where('tenant_id', $this->role->tenant_id ?? tenant('id'))
                    )
                    ->ignore($this->role->id),
            ],
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'role.name.required' => 'Role name is required.',
            'role.name.unique' => 'There is another role with the same name.',
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->role->fill([
            'slug' => str($this->role->name)->slug(),
        ])->save();

        if (!$this->role->wasRecentlyCreated) $this->popup('Role Updated.');

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.role.form');
    }
}