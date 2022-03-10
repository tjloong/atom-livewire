<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Register extends Component
{
    public $plan;
    public $user;
    public $signup;
    public $tenant;

    public $form = [
        'agree_tnc' => false,
        'agree_marketing' => true,
    ];
    
    protected $rules = [
        'form.name' => 'required|string|max:255',
        'form.email' => 'required|email|unique:users,email',
        'form.password' => 'required|min:8',
        'form.agree_tnc' => 'accepted',
        'form.agree_marketing' => 'nullable',
    ];

    protected $messages = [
        'form.name.required' => 'Name is required.',
        'form.name.string' => 'Invalid name.',
        'form.name.max' => 'Name has exceeded maximum characters allowed.',
        'form.email.required' => 'Login email is required.',
        'form.email.email' => 'Invalid email.',
        'form.email.unique' => 'This login email has been taken.',
        'form.password.required' => 'Login password is required.',
        'form.password.min' => 'Login password must be at least 8 characters.',
        'form.agree_tnc.accepted' => 'Please accept the terms and conditions to proceed.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        if (!request()->query('ref')) return redirect('/');

        $this->plan = request()->query('plan');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        if (enabled_module('tenants')) $this->createTenant();

        $this->createUser();

        if (enabled_module('signups')) $this->createSignup();

        $this->registered();

        return redirect($this->redirectTo());
    }

    /**
     * Create tenant
     */
    public function createTenant()
    {
        //
    }

    /**
     * Create user
     */
    public function createUser()
    {
        $this->user = model('user');

        $this->user->fill([
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'password' => bcrypt($this->form['password']),
            'is_root' => false,
            'is_pending' => false,
            'is_active' => true,
        ]);

        $this->user->save();
    }

    /**
     * Create signup
     */
    public function createSignup()
    {
        $this->signup = $this->user->signup()->create([
            'agree_tnc' => $this->form['agree_tnc'],
            'agree_marketing' => $this->form['agree_marketing'],
        ]);
    }

    /**
     * Post registration
     */
    public function registered()
    {
        if (config('atom.signups.verify')) {
            $this->user->sendEmailVerificationNotification();
        }

        Auth::login($this->user);

        // clear refcode
        Cookie::expire('_ref');
    }

    /**
     * Redirect after registration
     */
    public function redirectTo()
    {
        if (enabled_module('plans') && model('plan')->where('is_active', true)->count()) {
            return route('billing', ['plan' => $this->plan]);
        }

        return route('onboarding');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::auth.register')->layout('layouts.auth');
    }
}