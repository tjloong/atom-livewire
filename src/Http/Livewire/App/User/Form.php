<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $user;
    public $teams;

    /**
     * Validation
     */
    protected function validation(): array
    {
        $rules = [
            'user.name' => [
                'required' => 'Name is required.',
                'string' => 'Invalid name.',
                'max:255' => 'Name is too long (Max 255 characters).',
            ],
            'user.email' => [
                'required' => 'Login email is required.',
                'email' => 'Invalid login email.',
                function ($attr, $value, $fail) {
                    if (model('user')
                        ->readable()
                        ->where('email', $value)
                        ->where('id', '<>', $this->user->id)
                        ->count()
                    ) {
                        $fail('Login email is taken.');
                    }
                },
            ],
            'user.visibility' => ['nullable'],
            'user.activated_at' => ['nullable'],
            'user.is_root' => ['nullable'],
        ];

        if (enabled_module('roles')) $rules['user.role_id'] = ['nullable'];

        return $rules;
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->teams = enabled_module('teams') ? $this->user->teams->pluck('id')->toArray() : null;
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return array_filter([
            'roles' => enabled_module('roles') ? model('role')->readable()->get() : null,
            'teams' => enabled_module('teams') ? model('team')->readable()->get() : null,
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
    public function getCanProperty(): array
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
    public function updatedUserIsRoot($val): void
    {
        if ($val) $this->user->fill(['visibility' => 'global']);
    }

    /**
     * Updated user role id
     */
    public function updatedUserRoleId($id): void
    {
        if ($this->user->isRole('admin')) $this->user->fill(['visibility' => 'global']);
    }

    /**
     * Resend activation email
     */
    public function resendActivationEmail(): void
    {
        $this->user->sendActivation();

        $this->popup('Activation email sent.', 'alert');
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->persist();

        return $this->user->wasRecentlyCreated
            ? breadcrumbs()->back()
            : $this->popup('User Updated.');
    }

    /**
     * Persist
     */
    public function persist(): void
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
    public function render(): mixed
    {
        return atom_view('app.user.form');
    }
}