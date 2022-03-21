<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $user;
    public $selectedTeams;
    public $sendAccountActivationEmail;

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'user.name' => 'required',
            'user.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'user.visibility' => 'nullable',
            'user.activated_at' => 'nullable',
            'user.account_id' => 'required',
        ];

        if (enabled_module('roles')) $rules['role_id'] = 'nullable';

        return $rules;
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'user.name.required' => __('Name is required.'),
            'user.email.required' => __('Login email is required.'),
            'user.email.email' => __('Invalid email address.'),
            'user.email.unique' => __('Login email is already taken.'),
            'user.account_id.required' => __('Unknown account'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->sendAccountActivationEmail = !$this->user->exists;

        if (enabled_module('teams')) {
            $this->selectedTeams = $this->user->teams->pluck('id')->toArray();
        }
    }

    /**
     * Get role property
     */
    public function getRoleProperty()
    {
        if (!enabled_module('roles')) return;

        return model('role')->find($this->user->role_id);
    }

    /**
     * Get roles property
     */
    public function getRolesProperty()
    {
        if (!enabled_module('roles')) return [];

        return model('role')->orderBy('name')->get()
            ->map(fn($val) => ['value' => $val->id, 'label' => $val->name]);
    }

    /**
     * Get teams property
     */
    public function getTeamsProperty()
    {
        if (!enabled_module('teams')) return [];

        return model('team')->orderby('name')->get()
            ->map(fn($val) => ['value' => $val->id, 'label' => $val->name]);
    }

    /**
     * Get visibilities property
     */
    public function getVisibilitiesProperty()
    {
        return array_filter([
            ['value' => 'restrict', 'caption' => 'Can view data created by ownself.'],
            enabled_module('teams') ? ['value' => 'team', 'caption' => 'Can view data created by ownself and team members.'] : null,
            ['value' => 'global', 'caption' => 'Can view all data.'],
        ]);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $sendVerifyEmail = $this->user->exists 
            && config('atom.accounts.verify')
            && $this->user->isDirty('email');

        $this->persist();        

        if ($sendVerifyEmail) {
            $this->user->update(['email_verified_at' => null]);
            $this->user->sendEmailVerificationNotification();
        }

        if ($this->sendAccountActivationEmail) {
            $this->user->sendAccountActivation();
        }

        $this->sendAccountActivationEmail = false;
        $this->emitUp('saved');
    }

    /**
     * Persist
     */
    public function persist()
    {
        $this->user->save();

        if (enabled_module('teams')) {
            $this->user->teams()->sync($this->selectedTeams);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.form', [
            'role' => $this->role,
            'roles' => $this->roles,
            'teams' => $this->teams,
            'visibilities' => $this->visibilities,
        ]);
    }
}