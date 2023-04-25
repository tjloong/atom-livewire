<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Login extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $user;

    /**
     * Validation
     */
    protected function validation(): array
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

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->user = user();
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->user->save();

        $this->popup('Login Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.login');
    }
}