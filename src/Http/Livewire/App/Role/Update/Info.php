<?php

namespace Jiannius\Atom\Http\Livewire\App\Role\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Info extends Component
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
                    ->where(fn($q) => $q->where('account_id', auth()->user()->account_id))
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
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->role->fill([
            'slug' => str($this->role->name)->slug(),
        ])->save();

        if ($this->role->wasRecentlyCreated) return redirect()->route('app.settings', ['roles']);
        else $this->popup('Role Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.role.update.info');
    }
}