<?php

namespace Jiannius\Atom\Http\Livewire\App\User\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\WithPopupNotify;

class Info extends Component
{
    use WithPopupNotify;
    
    public $user;
    public $selectedTeams;

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
            'user.account_id' => 'nullable',
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
                ->when(model('role')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
                ->orderBy('name')
                ->get(),

            'teams' => model('team')
                ->when(model('team')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
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

        $this->persist();

        if ($this->user->wasRecentlyCreated) return redirect()->route('app.settings', ['users']);
        else $this->popup('User Updated');
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
        return view('atom::app.user.update.info');
    }
}