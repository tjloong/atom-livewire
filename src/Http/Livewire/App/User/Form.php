<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Jiannius\Atom\Models\Role;

class Form extends Component
{
    public $user;
    public $form;
    public $roles;
    public $sendVerifyEmail;
    public $sendAccountActivationEmail;

    protected $messages = [
        'form.name.required' => 'Name is required.',
        'form.email.required' => 'Login email is required.',
        'form.email.email' => 'Invalid email address.',
        'form.email.unique' => 'Login email is already taken.',
        'form.password.min' => 'Password must be at least 8 characters.',
        'form.role_id.required' => 'Role is required.',
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
            'form.role_id' => 'required',
        ];
    }

    /**
     * Mount component
     * 
     * @return void
     */
    public function mount()
    {
        $this->form = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => null,
            'role_id' => $this->default_role->id,
        ];
        
        $this->isSelf = $this->user->id === auth()->id();
        $this->sendVerifyEmail = false;
        $this->sendAccountActivationEmail = !$this->user->exists;

        if (enabled_feature('roles')) {
            $this->form['role_id'] = $this->user->role_id;
            $this->roles = Role::assignables()->get()->map(
                fn($role) => ['value' => $role->id, 'label' => $role->name]
            );
        }
    }

    /**
     * Get default role property
     */
    public function getDefaultRoleProperty()
    {
        return Role::where('slug', 'administrator')->where('is_system', true)->first();
    }

    /**
     * Save
     */
    public function save()
    {
        $this->resetValidation();
        $this->validate();

        $verify = $this->user->exists 
            && enabled_feature('auth.verify')
            && $this->form['email'] !== $this->user->email;

        $data = Arr::only($this->form, ['name', 'email', 'role_id']);
        $this->user->fill($data)->save();

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
        return view('atom::app.user.form');
    }
}