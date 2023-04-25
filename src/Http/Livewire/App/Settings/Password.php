<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Illuminate\Auth\Events\PasswordReset;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Password extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $password = [
        'current' => null,
        'new' => null,
        'new_confirmation' => null,
    ];

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'password.current' => [
                'required' => 'Current password is required.',
                'current_password' => 'Incorrect password.',
            ],
            'password.new' => [
                'required' => 'New password is required.',
                'min:8' => 'New password must be at least 8 characters.',
                'confirmed' => 'New password do not match with password confirmation.',
            ],
            'password.new_confirmation' => ['required' => 'Confirm new password is required.'],
        ];
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        user()->forceFill([
            'password' => bcrypt(data_get($this->password, 'new')),
        ])->save();

        event(new PasswordReset(user()));

        $this->reset('password');
        $this->popup('Password Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.password');
    }
}