<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Jiannius\Atom\Models\Role;

class Register extends Component
{
    public $user;
    public $form = [
        'agree_marketing' => true,
    ];
    
    protected $rules = [
        'form.name' => 'required|string|max:255',
        'form.email' => 'required|email|unique:users,email',
        'form.password' => 'required|min:8',
        'form.agree_tnc' => 'accepted',
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
     * 
     * @return void
     */
    public function mount()
    {
        if (auth()->user() || !request()->query('ref')) return redirect('/');
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::auth.register')->layout('layouts.auth');
    }

    /**
     * Form submission
     * 
     * @return void
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->createUser();
        $this->registered();

        return redirect($this->redirectTo());
    }

    /**
     * Create user
     * 
     * @return User
     */
    public function createUser()
    {
        $this->user = User::create([
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'password' => bcrypt($this->form['password']),
            'status' => 'active',
            'role_id' => Role::where('slug', 'restricted-user')->where('is_system', true)->first()->id ?? null,
        ]);
    }

    /**
     * Post registration
     * 
     * @return void
     */
    public function registered()
    {
        if (config('atom.features.auth.verify')) {
            $this->user->sendEmailVerificationNotification();
        }

        Auth::login($this->user);

        // clear refcode
        Cookie::expire('_ref');
    }

    /**
     * Redirect after registration
     * 
     * @return void
     */
    public function redirectTo()
    {
        return route('register.completed');
    }
}