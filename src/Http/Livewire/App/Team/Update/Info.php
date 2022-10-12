<?php

namespace Jiannius\Atom\Http\Livewire\App\Team\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Info extends Component
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
                Rule::unique('teams', 'name')
                    ->when(
                        Schema::hasColumn('teams', 'account_id'),
                        fn($q) => $q->where('account_id', auth()->user()->account_id)
                    )
                    ->ignore($this->team),
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
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->team->save();

        if ($this->team->wasRecentlyCreated) return redirect()->route('app.settings', ['teams']);
        else $this->popup('Team Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.update.info');
    }
}