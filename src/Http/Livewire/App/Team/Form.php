<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithPopupNotify;

    public $team;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'team.name' => [
                'required',
                function($attr, $value, $fail) {
                    if (model('team')->readable()->where('name', $value)->count()) {
                        $fail('There is another team with the same name.');
                    }
                },
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
            'team.name.required' => 'Team name is required.',
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->team->save();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.team.form');
    }
}