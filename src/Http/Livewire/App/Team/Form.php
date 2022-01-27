<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $team;

    protected function rules()
    {
        return [
            'team.name' => [
                'required',
                Rule::unique('teams', 'name')->ignore($this->team),
            ],
            'team.description' => 'nullable',
        ];
    }

    /**
     * Mount component
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.team.form');
    }

    /**
     * Save team
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();

        $this->team->save();

        $this->emitUp('saved');
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    private function validateinputs()
    {
        $this->resetValidation();

        $validator = validator(
            ['team' => $this->team],
            $this->rules(),
            [
                'team.name.required' => 'Team name is required.',
                'team.name.unique' => 'There is another team with the same name.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}