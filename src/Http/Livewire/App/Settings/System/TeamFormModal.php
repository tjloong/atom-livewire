<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class TeamFormModal extends Component
{
    use WithPopupNotify;

    public $team;

    protected $listeners = ['open'];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'team.name' => [
                'required',
                Rule::unique('teams', 'name')
                    ->when(
                        model('team')->enabledHasTenantTrait,
                        fn($q) => $q->where('tenant_id', $this->team->tenant_id ?? auth()->user()->tenant_id)
                    )
                    ->ignore($this->team->id),
            ],
            'team.description' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'team.name.required' => __('Team name is required.'),
            'team.name.unique' => __('There is another team with the same name.'),
        ];
    }

    /**
     * Open
     */
    public function open($id = null)
    {
        $this->team = $id
            ? model('team')->findOrFail($id)
            : model('team');

        $this->dispatchBrowserEvent('team-form-modal-open');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->team->save();

        if (!$this->team->wasRecentlyCreated) $this->popup('Team Updated.');

        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('team-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.system.team-form-modal');
    }
}