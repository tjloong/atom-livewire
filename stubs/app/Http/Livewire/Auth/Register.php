<?php

namespace App\Http\Livewire\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\SiteSetting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $agreeTnc;
    public $agreeMarketing = true;
    public $isCompleted;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'agreeTnc' => 'accepted',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'name.string' => 'Invalid name.',
        'name.max' => 'Name has exceeded maximum characters allowed.',
        'email.required' => 'Login email is required.',
        'email.email' => 'Invalid email.',
        'email.unique' => 'This login email has been taken.',
        'password.required' => 'Login password is required.',
        'password.min' => 'Login password must be at least 8 characters.',
        'agreeTnc.accepted' => 'Please accept the terms and conditions to proceed.',
    ];

    /**
     * Mount
     * 
     * @return void
     */
    public function mount($slug = null)
    {
        if ($slug === 'completed') $this->isCompleted = true;
        else if (auth()->user() || !request()->query('ref')) return redirect('/');
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }

    /**
     * Register
     */
    public function register()
    {
        $this->resetValidation();
        $this->validate();

        $role = Role::where('slug', 'administrator')->first();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'status' => 'active',
            'role_id' => $role->id ?? null,
        ]);

        if ($user->mustVerifyEmail) {
            $user->sendEmailVerificationNotification();
        }

        Auth::login($user);

        // clear refcode
        Cookie::expire('_ref');

        return redirect()->route('register', ['completed']);
    }
}