<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class RoleFormModal extends Component
{
    use WithPopupNotify;

    public $role;

    protected $listeners = ['open'];

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
                        model('role')->enabledBelongsToAccountTrait,
                        fn($q) => $q->where('account_id', $this->role->account_id ?? auth()->user()->account_id)
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
            'role.name.required' => __('Role name is required.'),
            'role.name.unique' => __('There is another role with the same name.'),
        ];
    }

    /**
     * Open
     */
    public function open($id = null)
    {
        $this->role = $id
            ? model('role')
                ->when(
                    model('role')->enabledBelongstoAccountTrait,
                    fn($q) => $q->belongsToAccount(),
                )
                ->findOrFail($id)
            : model('role');

        $this->dispatchBrowserEvent('role-form-modal-open');
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

        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('role-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.system.role-form-modal');
    }
}