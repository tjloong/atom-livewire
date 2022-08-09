<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Login extends Component
{
    public $user;

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'user.name' => 'required|string|max:255',
            'user.email' => [
                'required', 'email', 
                Rule::unique('users', 'email')->ignore(auth()->user()),
            ],
        ];
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'user.name.required' => __('Login name is required.'),
            'user.name.string' => __('Invalid login name.'),
            'user.name.max' => __('Login name is too long.'),
            'user.email.required' => __('Login email is required.'),
            'user.email.email' => __('Invalid login email.'),
            'user.email.unique' => __('Login email is taken.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->user = auth()->user();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->user->save();

        $this->dispatchBrowserEvent('toast', [
            'message' => __('Updated Personal Information'), 
            'type' => 'success',
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.login');
    }
}