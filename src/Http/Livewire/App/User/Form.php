<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $user;
    public $form;
    public $isSelf;
    public $selectedTeams;
    public $sendVerifyEmail;
    public $sendAccountActivationEmail;

    protected $messages = [
        'form.name.required' => 'Name is required.',
        'form.email.required' => 'Login email is required.',
        'form.email.email' => 'Invalid email address.',
        'form.email.unique' => 'Login email is already taken.',
        'form.password.min' => 'Password must be at least 8 characters.',
    ];

    protected function rules()
    {
        return [
            'form.name' => 'required',
            'form.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'form.password' => 'nullable|min:8',
            'form.visibility' => 'nullable',
            'form.role_id' => 'nullable',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->form = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => null,
            'visibility' => $this->user->visibility ?? 'global',
            'role_id' => $this->user->role_id,
        ];
        
        $this->isSelf = $this->user->id === auth()->id();
        $this->selectedTeams = $this->user->teams->pluck('id')->toArray();
        $this->sendVerifyEmail = false;
        $this->sendAccountActivationEmail = !$this->user->exists;
    }

    /**
     * Get role property
     */
    public function getRoleProperty()
    {
        return model('role')->find($this->form['role_id']);
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
     * Updated form.role_id
     */
    public function updatedFormRoleId($value)
    {
        if (empty($value)) $this->form['role_id'] = null;
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $verify = $this->user->exists 
            && config('atom.auth.verify')
            && $this->form['email'] !== $this->user->email;

        $this->user
            ->fill(Arr::only($this->form, ['name', 'email', 'visibility', 'role_id']))
            ->save();

        $this->user->teams()->sync($this->selectedTeams);

        if ($password = $this->form['password'] ? bcrypt($this->form['password']) : null) {
            if ($this->isSelf) {
                $this->user->password = $password;
                $this->user->save();
            }
        }

        if ($verify) {
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