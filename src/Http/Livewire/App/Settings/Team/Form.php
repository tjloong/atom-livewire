<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Team;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $team;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'team.name' => [
                'required' => 'Team name is required.',
                function($attr, $value, $fail) {
                    if (model('team')->readable()->where('name', $value)->where('id', '<>', $this->team->id)->count()) {
                        $fail('There is another team with the same name.');
                    }
                },
            ],
            'team.description' => ['nullable'],
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->team->save();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.team.form');
    }
}