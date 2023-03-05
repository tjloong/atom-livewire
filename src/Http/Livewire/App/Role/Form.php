<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

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
                function ($attr, $value, $fail) {
                    if (model('role')
                        ->readable()
                        ->where('name', $value)
                        ->where('id', '<>', $this->role->id)
                        ->count()
                    ) {
                        $fail('There is another role with the same name.');
                    }
                },
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
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        if ($this->role->isDirty('name')) $this->role->fill(['slug' => null]);

        $this->role->save();

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