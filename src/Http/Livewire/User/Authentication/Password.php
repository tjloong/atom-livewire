<?php

namespace Jiannius\Atom\Http\Livewire\User\Authentication;

use Livewire\Component;

class Password extends Component
{
    public $user;
    public $password = [
        'current' => null,
        'new' => null,
        'new_confirmation' => null,
    ];

    protected $messages = [
        'password.current.required' => 'Current password is required.',
        'password.current.current_password' => 'Incorrect password.',
        'password.new.required' => 'New password is required.',
        'password.new.min' => 'New password must be at least 8 characters.',
        'password.new.confirmed' => 'New password do not match with password confirmation.',
        'password.new_confirmation.required' => 'Confirm new password is required.',
    ];

    protected function rules() {
        return [
            'password.current' => 'required|current_password',
            'password.new' => 'required|min:8|confirmed',
            'password.new_confirmation' => 'required',
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->user->password = bcrypt($this->password['new']);
        $this->user->save();

        $this->reset('password');
        $this->dispatchBrowserEvent('toast', ['message' => 'Updated Password', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::user.authentication.password')->layout('layouts.user');
    }
}