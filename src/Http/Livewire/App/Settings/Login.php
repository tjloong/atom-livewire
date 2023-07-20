<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Login extends Component
{
    use WithForm;
    use WithLoginMethods;
    use WithPopupNotify;

    public $user;

    // validation
    protected function validation(): array
    {
        return array_merge(
            [
                'user.name' => [
                    'required' => 'Login name is required.',
                    'max:255' => 'Login name is too long (max 255 characters).',
                ],
            ],

            $this->isLoginMethod('username') ? [
                'user.username' => [
                    'required' => 'Username is required.', 
                    'max:255' => 'Username is too long (max 255 characters).', 
                    function ($attr, $value, $fail) {
                        if (model('user')->where('username', $value)->where('id', '<>', user('id'))->count()) {
                            $fail('Username is taken.');
                        }
                    },
                ],
            ] : [],

            $this->isLoginMethod('email') ? [
                'user.email' => [
                    'required' => 'Login email is required.', 
                    'email' => 'Invalid login email', 
                    function ($attr, $value, $fail) {
                        if (model('user')->where('email', $value)->where('id', '<>', user('id'))->count()) {
                            $fail('Login email is taken.');
                        }
                    },
                ],
            ] : [],
        );
    }

    // mount
    public function mount(): void
    {
        parent::mount();

        $this->user = user();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();
        $this->user->save();
        $this->popup('Login Updated.');
    }
}