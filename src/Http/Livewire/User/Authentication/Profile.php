<?php

namespace Jiannius\Atom\Http\Livewire\User\Authentication;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Profile extends Component
{
    public $user;

    protected function rules() {
        return [
            'user.name' => 'required|string|max:255',
            'user.email' => [
                'required', 'email', 
                Rule::unique('users', 'email')->ignore($this->user),
            ],
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->user->save();

        $this->dispatchBrowserEvent('toast', ['message' => 'Updated Profile Information', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::user.authentication.profile')->layout('layouts.user');
    }
}