<?php

namespace Jiannius\Atom\Http\Livewire\App\User\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Info extends Component
{
    public $user;
    public $selectedTeams;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'user.name' => 'required|string|max:255',
            'user.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'user.visibility' => 'nullable',
            'user.activated_at' => 'nullable',
            'user.role_id' => 'nullable',
            'user.account_id' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'user.name.required' => __('Name is required.'),
            'user.name.max' => __('Name is too long (Max 255 characters).'),
            'user.email.required' => __('Login email is required.'),
            'user.email.email' => __('Invalid email address.'),
            'user.email.unique' => __('Login email is already taken.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (enabled_module('teams')) {
            $this->selectedTeams = $this->user->teams->pluck('id')->toArray();
        }
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'roles' => model('role')
                ->select('id as value', 'name as label')
                ->orderBy('name')
                ->get(),

            'teams' => model('team')
                ->select('id as value', 'name as label')
                ->orderBy('name')
                ->get(),

            'visibilities' => array_filter([
                ['value' => 'restrict', 'label' => 'Can view data created by ownself.'],
                enabled_module('teams') 
                    ? ['value' => 'team', 'label' => 'Can view data created by ownself and team members.'] 
                    : null,
                ['value' => 'global', 'label' => 'Can view all data.'],    
            ]),
        ];
    }

    /**
     * Resend activation email
     */
    public function resendActivationEmail()
    {
        $this->user->sendAccountActivation();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->user->save();

        if (enabled_module('teams')) {
            $this->user->teams()->sync($this->selectedTeams);
        }

        if ($this->user->wasRecentlyCreated) {
            return redirect()->route('app.user.listing');
        }
        else {
            $this->dispatchBrowserEvent('toast', [
                'message' => __('User Updated'),
                'type' => 'success',
            ]);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.update.info');
    }
}