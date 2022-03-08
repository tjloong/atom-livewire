<?php

namespace Jiannius\Atom\Http\Livewire\Onboarding;

use Livewire\Component;

class Profile extends Component
{
    public $signup;

    protected $rules = [
        'signup.email' => 'required|email|nullable',
        'signup.phone' => 'required',
        'signup.gender' => 'required',
        'signup.dob' => 'nullable',
        'signup.address' => 'nullable',
        'signup.city' => 'nullable',
        'signup.postcode' => 'nullable',
        'signup.state' => 'nullable',
        'signup.country' => 'nullable',
    ];

    protected $messages = [
        'signup.email.email' => 'Invalid email address.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        $this->signup->email = $this->signup->email ?? $this->signup->user->email;
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->signup->save();
        $this->emitUp('next');
        $this->dispatchBrowserEvent('toast', ['message' => 'Profile Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::onboarding.profile', ['countries' => metadata()->countries()]);
    }
}