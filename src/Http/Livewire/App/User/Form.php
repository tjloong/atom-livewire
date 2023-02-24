<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithPopupNotify;

    public $user;
    public $teams;

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'user.name' => 'required|string|max:255',
            'user.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'user.visibility' => 'nullable',
            'user.activated_at' => 'nullable',
            'user.is_root' => 'nullable',
        ];

        if (enabled_module('roles')) $rules['user.role_id'] = 'nullable';

        return $rules;
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'user.name.required' => 'Name is required.',
            'user.name.max' => 'Name is too long (Max 255 characters).',
            'user.email.required' => 'Login email is required.',
            'user.email.email' => 'Invalid email address.',
            'user.email.unique' => 'Login email is already taken.',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->teams = enabled_module('teams') ? $this->user->teams->pluck('id')->toArray() : null;
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return array_filter([
            'roles' => model('role')->readable()->get(),
            'teams' => model('team')->readable()->get(),
            'visibilities' => array_filter([
                ['value' => 'restrict', 'label' => 'Restrict', 'description' => 'Can view data created by ownself.'],
                enabled_module('teams') 
                    ? ['value' => 'team', 'label' => 'Team', 'description' => 'Can view data created by ownself and team members.'] 
                    : null,
                ['value' => 'global', 'label' => 'Global', 'description' => 'Can view all data.'],    
            ]),
        ]);
    }

    /**
     * Get can property
     */
    public function getCanProperty()
    {
        return [
            'name' => !$this->user->exists || tier('root'),
            'email' => !$this->user->exists || tier('root'),
            'role' => enabled_module('roles') && (!$this->user->exists || tier('root')),
            'team' => enabled_module('teams') && (!$this->user->exists || tier('root')),
            'root' => false,
            'visibility' => !$this->user->exists && tier('signup') && !role('admin'),
        ];
    }

    /**
     * Update user is root
     */
    public function updatedUserIsRoot($val)
    {
        if ($val) $this->user->fill(['visibility' => 'global']);
    }

    /**
     * Updated user role id
     */
    public function updatedUserRoleId($id)
    {
        if ($this->user->isRole('admin')) $this->user->fill(['visibility' => 'global']);
    }

    /**
     * Resend activation email
     */
    public function resendActivationEmail()
    {
        $this->user->sendActivation();

        $this->popup('Activation email sent.', 'alert');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->persist();

        if ($this->user->wasRecentlyCreated) return breadcrumbs()->back();
        else $this->popup('User Updated.');
    }

    /**
     * Persist
     */
    public function persist()
    {
        $this->user->save();

        if (enabled_module('teams')) {
            $this->user->teams()->sync($this->teams);
        }

        if (enabled_module('tenants') && tenant()) {
            tenant()->users()->attach([
                $this->user->id => ['invited_at' => now()],
            ]);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.user.form');
    }
}