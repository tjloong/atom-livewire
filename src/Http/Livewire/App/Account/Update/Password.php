<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Password extends Component
{
    public $password = [
        'current' => null,
        'new' => null,
        'new_confirmation' => null,
    ];

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'password.current' => 'required|current_password',
            'password.new' => 'required|min:8|confirmed',
            'password.new_confirmation' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'password.current.required' => __('Current password is required.'),
            'password.current.current_password' => __('Incorrect password.'),
            'password.new.required' => __('New password is required.'),
            'password.new.min' => __('New password must be at least 8 characters.'),
            'password.new.confirmed' => __('New password do not match with password confirmation.'),
            'password.new_confirmation.required' => __('Confirm new password is required.'),
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        auth()->user()->fill([
            'password' => bcrypt($this->password['new']),
        ])->save();

        $this->reset('password');

        $this->dispatchBrowserEvent('toast', [
            'message' => __('Updated Password'), 
            'type' => 'success',
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.password');
    }
}