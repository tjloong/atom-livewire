<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Register extends Component
{
    public $ref;
    public $plan;
    public $user;
    public $account;
    public $redirect;

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

    protected $queryString = ['ref', 'redirect'];

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->ref) return redirect('/');

        $this->plan = request()->query('plan');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        if (enabled_module('accounts')) {
            $this->createAccount();
            $this->accountMetadata();
        }

        $this->createUser();
        $this->registered();

        return redirect($this->redirectTo());
    }

    /**
     * Create account
     */
    public function createAccount()
    {
        $this->account = model('account')->create([
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'agree_tnc' => $this->form['agree_tnc'],
            'agree_marketing' => $this->form['agree_marketing'],
        ]);
    }

    /**
     * Create user
     */
    public function createUser()
    {
        $this->user = model('user');

        $this->user->name = $this->form['name'];
        $this->user->email = $this->form['email'];
        $this->user->password = bcrypt($this->form['password']);
        $this->user->is_root = false;
        $this->user->is_pending = false;
        $this->user->is_active = true;

        if ($this->account) $this->user->account_id = $this->account->id;

        $this->user->save();
    }

    /**
     * Update account metadata
     */
    public function accountMetadata()
    {
        $this->account->data = [
            'register_geo' => geoip()->getLocation()->toArray(),
            'register_channel' => $this->ref,
        ];

        $this->account->save();
    }

    /**
     * Post registration
     */
    public function registered()
    {
        if (config('atom.accounts.verify')) {
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

        return route('onboarding', $this->redirect
            ? ['redirect' => $this->redirect]
            : []
        );
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::auth.register')->layout('layouts.auth');
    }
}