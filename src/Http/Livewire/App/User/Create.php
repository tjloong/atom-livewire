<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Create extends Component
{
    use WithForm;

    public $user;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'user.name' => [
                'required' => 'Name is required.',
                'string' => 'Invalid name.',
                'max:255' => 'Name is too long (Max 255 characters).',
            ],
            'user.email' => [
                'required' => 'Login email is required.',
                'email' => 'Invalid login email.',
                function ($attr, $value, $fail) {
                    if (model('user')->where('email', $value)->count()) {
                        $fail('Login email is taken.');
                    }
                },
            ],
            'user.is_root' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->user = model('user')->fill(['is_root' => tier('root')]);

        breadcrumbs()->push('Create User');
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->user->save();

        return redirect()->route('app.user.update', [$this->user->id]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.user.create');
    }
}