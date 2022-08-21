<?php

namespace Jiannius\Atom\Http\Livewire\App\Team\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;

class Info extends Component
{
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

        if ($this->team->wasRecentlyCreated) {
            return redirect()->route('app.team.listing');
        }
        else {
            $this->dispatchBrowserEvent('toast', [
                'message' => __('Team Updated.'),
                'type' => 'success',
            ]);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.update.info');
    }
}