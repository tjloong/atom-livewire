<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Profile;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Login extends Component
{
    use WithForm;

    public $user;

    // validation
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
        ];
    }

    // mount
    public function mount() : void
    {
        $this->user = user();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->user->save();
        Atom::toast('updated', 'success');
    }
}