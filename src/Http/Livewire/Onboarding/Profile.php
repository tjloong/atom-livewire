<?php

namespace Jiannius\Atom\Http\Livewire\Onboarding;

use Livewire\Component;

class Profile extends Component
{
    public $account;

    protected $rules = [
        'account.name' => 'required|string|max:255',
        'account.email' => 'required|email|nullable',
        'account.phone' => 'required',
        'account.gender' => 'required',
        'account.dob' => 'nullable',
        'account.address' => 'nullable',
        'account.city' => 'nullable',
        'account.postcode' => 'nullable',
        'account.state' => 'nullable',
        'account.country' => 'nullable',
    ];

    protected $messages = [
        'account.name.required' => 'Name is required.',
        'account.name.string' => 'Invalid name',
        'account.email.email' => 'Invalid email address.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->account->save();
        
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