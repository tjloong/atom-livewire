<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Role;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $role;

    protected $listeners = ['open'];

    // validation
    protected function validation(): array
    {
        return [
            'role.name' => [
                'required' => 'Role name is required.',
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

    // open
    public function open($data = null): void
    {
        $id = is_numeric($data) ? $data : data_get($data, 'id');

        $this->role = $id 
            ? model('role')->readable()->findOrFail($id) 
            : model('role')->fill($data ?? []);

        $this->resetValidation();
        $this->dispatchBrowserEvent('role-form-open');
    }

    // close
    public function close(): void
    {
        $this->role = null;
        $this->emit('refresh');
        $this->dispatchBrowserEvent('role-form-close');
    }

    // delete
    public function delete(): void
    {
        if ($this->role->users()->count() > 0) $err = 'This role has users assigned to it.';
        if ($this->role->is_admin && model('role')->readable()->isAdmin()->count() <= 1) $err = 'You must have at least 1 admin role.';

        if (isset($err)) {
            $this->popup([
                'title' => 'Unable to Delete', 
                'message' => $err,
            ], 'alert', 'error');
        }
        else {
            $this->role->delete();
            $this->close();
        }
    }


    // submit
    public function submit(): void
    {
        $this->validateForm();

        if ($this->role->isDirty('name')) $this->role->fill(['slug' => null]);

        $this->role->save();

        $this->close();
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.settings.role.form');
    }
}