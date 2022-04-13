<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Register extends Component
{
    public $ref;
    public $plan;
    public $price;
    public $user;
    public $account;
    public $redirect;

    public $form = [
        'agree_tnc' => false,
        'agree_marketing' => true,
    ];

    protected $queryString = ['ref', 'plan', 'price', 'redirect'];
    
    /**
     * Validation rules
     */
    protected function rules() 
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|unique:users,email',
            'form.password' => 'required|min:8',
            'form.agree_tnc' => 'accepted',
            'form.agree_marketing' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'form.name.required' => __('Name is required.'),
            'form.name.string' => __('Invalid name.'),
            'form.name.max' => __('Name has exceeded maximum characters allowed.'),
            'form.email.required' => __('Login email is required.'),
            'form.email.email' => __('Invalid email.'),
            'form.email.unique' => __('This login email has been taken.'),
            'form.password.required' => __('Login password is required.'),
            'form.password.min' => __('Login password must be at least 8 characters.'),
            'form.agree_tnc.accepted' => __('Please accept the terms and conditions to proceed.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->ref) return redirect('/');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->createAccount();
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
            'type' => 'signup',
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'agree_tnc' => $this->form['agree_tnc'],
            'agree_marketing' => $this->form['agree_marketing'],
            'data' => $this->accountMetadata(),
        ]);

        $this->account->accountSetting()->create([
            'timezone' => config('atom.timezone'), 
            'locale' => head(config('atom.locales', [])) ?? null,
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
        $this->user->activated_at = now();
        $this->user->account_id = $this->account->id;

        $this->user->save();
    }

    /**
     * Get account metadata
     */
    public function accountMetadata()
    {
        return [
            'register_geo' => geoip()->getLocation()->toArray(),
            'register_channel' => $this->ref,
        ];
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
        if ($this->user->canAccessPortal('billing')) {
            if ($this->plan && $this->price) {
                return route('billing.checkout', ['plan' => $this->plan, 'price' => $this->price]);
            }
            else {
                return route('billing.plans');
            }
        }
        else if ($this->user->canAccessPortal('onboarding')) {
            return route('onboarding', array_filter(['redirect' => $this->redirect]));
        }

        return route('page');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::auth.register')->layout('layouts.auth');
    }
}