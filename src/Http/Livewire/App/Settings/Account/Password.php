<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Account;

use Illuminate\Auth\Events\PasswordReset;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Password extends Component
{
    use WithPopupNotify;

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

        auth()->user()->forceFill([
            'password' => bcrypt(data_get($this->password, 'new')),
        ])->save();

        event(new PasswordReset(auth()->user()));

        $this->reset('password');
        $this->popup('Updated Password');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.account.password');
    }
}