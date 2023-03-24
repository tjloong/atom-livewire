<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $role;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'role.name' => [
                'required' => 'Role name is required.',
                function ($attr, $value, $fail) {
                    if (model('role')->readable()->where('name', $value)->where('id', '<>', $this->role->id)->count()) {
                        $fail('There is another role with the same name.');
                    }
                },
            ],
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        if ($this->role->isDirty('name')) $this->role->fill(['slug' => null]);

        $this->role->save();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.role.form');
    }
}