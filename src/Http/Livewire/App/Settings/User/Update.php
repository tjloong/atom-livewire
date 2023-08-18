<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Update extends Component
{
    use WithForm;
    use WithLoginMethods;
    use WithPopupNotify;

    public $user;
    public $inputs;

    protected $listeners = [
        'createUser' => 'open',
        'updateUser' => 'open',
    ];

    // validation
    protected function validation(): array
    {
        return array_merge(
            [
                'inputs.password' => array_merge(
                    optional($this->user)->exists ? ['nullable'] : ['required' => 'Password is required.'],
                    ['min:8' => 'Password must be at least 8 characters.'],
                ),
                'user.name' => [
                    'required' => 'Name is required.',
                    'max:255' => 'Name is too long (Max 255 characters).',
                ],
            ],

            $this->isLoginMethod('email') ? [
                'user.email' => [
                    'required_without:user.username' => 'Login email is required.',
                    function ($attr, $value, $fail) {
                        if ($value && model('user')
                            ->where('email', $value)
                            ->where('id', '<>', $this->user->id)
                            ->count()
                        ) {
                            $fail('Login email is taken.');
                        }
                    },
                ],
            ] : [],

            $this->isLoginMethod('username') ? [
                'user.username' => [
                    'required_without:user.email' => 'Username is required.',
                    'max:255' => 'Username is too long (Max 255 characters).',
                    function ($attr, $value, $fail) {
                        if ($value && model('user')
                            ->where('username', $value)
                            ->where('id', '<>', $this->user->id)
                            ->count()
                        ) {
                            $fail('Username is taken.');
                        }
                    }
                ],
            ] : [],

            has_table('roles') && !optional($this->user)->isTier('root') ? [
                'user.role_id' => ['required' => 'Role is required.'],
            ] : [],
        );
    }

    // open
    public function open($id = null): void
    {
        if ($this->user = $id
            ? model('user')->readable()->withTrashed()->find($id)
            : model('user')
        ) {
            $this->fill([
                'inputs.password' => null,
                'inputs.is_root' => $this->user->exists ? $this->user->isTier('root') : tier('root'),
                'inputs.is_blocked' => !empty($this->user->blocked_at),
            ]);

            if (has_table('teams')) {
                $this->fill([
                    'inputs.teams' => $this->user->teams->pluck('id')->toArray(),
                ]);
            }

            $this->resetValidation();
            $this->dispatchBrowserEvent('user-update-open');
        }
    }

    // close
    public function close(): void
    {
        $this->emit('userSaved');
        $this->dispatchBrowserEvent('user-update-close');
    }

    // trash
    public function trash(): void
    {
        if ($this->user->id === user('id')) {
            $this->popup([
                'title' => 'Unable To Delete User',
                'message' => 'You cannot delete yourself.',
            ], 'alert', 'error');
        }
        else {
            $this->user->delete();
            $this->close();
        }
    }

    // delete
    public function delete(): void
    {
        $this->user->forceDelete();
        $this->close();
    }

    // restore
    public function restore(): void
    {
        $this->user->restore();
        $this->close();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        $this->user->fill([
            'tier' => data_get($this->inputs, 'is_root') ? 'root' : 'normal',
            'blocked_at' => data_get($this->inputs, 'is_blocked')
                ? ($this->user->blocked_at ?? now())
                : null,
        ])->save();

        if ($password = data_get($this->inputs, 'password')) {
            $this->user->forceFill(['password' => bcrypt($password)])->save();
        }

        if (has_table('teams')) {
            $this->user->teams()->sync(data_get($this->inputs, 'teams'));
        }

        $this->close();
    }
}