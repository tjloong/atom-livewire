<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $team;

    protected $messages = [
        'team.name.required' => 'Team name is required.',
        'team.name.unique' => 'There is another team with the same name.',
    ];

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
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->team->save();

        $this->emitUp('saved');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.form');
    }
}