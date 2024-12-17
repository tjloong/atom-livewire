<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Illuminate\Auth\Events\PasswordReset;
use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Profile extends Component
{
    use AtomComponent;

    public $user;

    public $password = [
        'current' => null,
        'new' => null,
        'new_confirmation' => null,
    ];

    protected function validation() : array
    {
        return [
            'user.name' => [
                'required' => 'Login name is required.',
                'max:255' => 'Login name is too long (max 255 characters).',
            ],
            'user.email' => [
                'required' => 'Login email is required.', 
                'email' => 'Invalid login email', 
                function ($attr, $value, $fail) {
                    if (model('user')->where('email', $value)->where('id', '<>', user('id'))->count()) {
                        $fail('Login email is taken.');
                    }
                },
            ],

            'password.current' => [
                'required_with:password.new' => 'Current password is required.',
                'required_with:password.new_confirmation' => 'Current password is required.',
                'nullable',
                'current_password' => 'Incorrect password.',
            ],
            'password.new' => [
                'required_with:password.new_confirmation' => 'New password is required.',
                'nullable',
                'min:8' => 'New password must be at least 8 characters.',
                'confirmed' => 'New password do not match with password confirmation.',
            ],
            'password.new_confirmation' => [
                'required_with:password.new' => 'Confirm new password is required.',
            ],
        ];
    }

    public function mount()
    {
        $this->user = user();
    }

    public function submit() : void
    {
        $this->validate();

        $this->user->save();

        if (get($this->password, 'new')) {
            user()->forceFill([
                'password' => bcrypt(get($this->password, 'new')),
            ])->save();
            
            event(new PasswordReset(user()));

            $this->reset('password');
        }

        Atom::toast('updated', 'success');
    }
}
