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
        if (enabled_module('teams')) $this->teams = $this->user->teams->pluck('id')->toArray();
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return array_filter([
            'roles' => enabled_module('roles') ? model('role')->assignable()->get() : null,
            'teams' => enabled_module('teams') ? model('team')->assignable()->get() : null,
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
     * Update user is root
     */
    public function updatedUserIsRoot($val)
    {
        if ($val) $this->user->fill(['visibility' => 'global']);
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
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.user.form');
    }
}