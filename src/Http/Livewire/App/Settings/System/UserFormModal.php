<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class UserFormModal extends Component
{
    use WithPopupNotify;

    public $user;
    public $teams;
    public $account;

    protected $listeners = ['open'];

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
            'user.account_id' => 'nullable',
            'user.role_id' => 'nullable',
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
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'roles' => model('role')->belongsToAccount()->orderBy('name')->get(),
            'teams' => model('team')->belongsToAccount()->orderBy('name')->get(),

            'visibilities' => array_filter([
                ['value' => 'restrict', 'label' => 'Restrict', 'description' => 'Can view data created by ownself.'],
                enabled_module('teams') 
                    ? ['value' => 'team', 'label' => 'Team', 'description' => 'Can view data created by ownself and team members.'] 
                    : null,
                ['value' => 'global', 'label' => 'Global', 'description' => 'Can view all data.'],    
            ]),
        ];
    }

    /**
     * Open
     */
    public function open($id, $data = null)
    {
        if ($id) $this->user = model('user')->findOrFail($id);
        else {
            $this->user = model('user')->fill(array_merge(
                [
                    'visibility' => 'restrict',
                    'account_id' => auth()->user()->account_id,
                ],
                $data ?? [],
            ));

            if ($this->account && auth()->user()->isAccountType('root')) {
                $this->user->fill(['account_id' => $this->account->id]);
            }
        }

        if (enabled_module('teams')) {
            $this->teams = $this->user->teams->pluck('id')->toArray();
        }
    
        $this->dispatchBrowserEvent('user-form-modal-open');
    }

    /**
     * Resend activation email
     */
    public function resendActivationEmail()
    {
        $this->user->sendAccountActivation();

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

        if (!$this->user->wasRecentlyCreated) $this->popup('User Updated');

        $this->dispatchBrowserEvent('user-form-modal-close');
        $this->emitUp('refresh');
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
        return atom_view('app.settings.system.user-form-modal');
    }
}