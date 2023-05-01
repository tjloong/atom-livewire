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
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->user->save();

        if (enabled_module('teams')) {
            $this->user->teams()->sync($this->teams);
        }

        return $this->user->wasRecentlyCreated
            ? breadcrumbs()->back()
            : $this->popup('User Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.form');
    }
}