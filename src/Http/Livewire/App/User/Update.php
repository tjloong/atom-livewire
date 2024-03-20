<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $user;
    public $inputs;

    protected $listeners = [
        'createUser' => 'create',
        'updateUser' => 'update',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'inputs.password' => array_merge(
                optional($this->user)->exists ? ['nullable'] : ['required' => 'Password is required.'],
                ['min:8' => 'Password must be at least 8 characters.'],
            ),
            'user.email' => [
                'required' => 'Login email is required.',
                'email' => 'Invalid email address.',
                function ($attr, $value, $fail) {
                    if (model('user')->where('email', $value)->where('id', '<>', $this->user->id)->count()) {
                        $fail('Login email is taken.');
                    }
                },
            ],
            'user.name' => [
                'required' => 'Name is required.',
                'max:255' => 'Name is too long (Max 255 characters).',
            ],
        ];
    }

    // create
    public function create() : void
    {
        $this->user = model('user');
        $this->open();
    }

    // update
    public function update($id) : void
    {
        $this->user = model('user')->withTrashed()->find($id);
        $this->open();
    }


    // open
    public function open() : void
    {
        if ($this->user) {
            $this->resetValidation();

            $this->fill([
                'inputs.password' => null,
                'inputs.is_root' => $this->user->exists ? $this->user->isTier('root') : tier('root'),
                'inputs.is_blocked' => !empty($this->user->blocked_at),
                'inputs.permissions' => $this->user->getPermissionsList(),
            ]);

            $this->openDrawer('user-update');
        }
    }

    // close
    public function close() : void
    {
        $this->closeDrawer('user-update');
    }

    // trash
    public function trash() : void
    {
        if ($this->user->id === user('id')) {
            $this->popup([
                'title' => 'atom::settings.user.delete.failed.self.title',
                'message' => 'atom::settings.user.delete.failed.self.message',
            ], 'alert', 'error');
        }
        else {
            $this->user->delete();
            $this->emit('userDeleted');
            $this->close();
        }
    }

    // delete
    public function delete() : void
    {
        $this->user->forceDelete();
        $this->emit('userDeleted');
        $this->close();
    }

    // restore
    public function restore() : void
    {
        $this->user->restore();
        $this->emit('userUpdated');
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->user->fill([
            'tier' => data_get($this->inputs, 'is_root') ? 'root' : 'normal',
            'blocked_at' => data_get($this->inputs, 'is_blocked')
                ? ($this->user->blocked_at ?? now())
                : null,
        ]);

        if ($password = data_get($this->inputs, 'password')) {
            $this->user->forceFill(['password' => bcrypt($password)]);
        }
        
        $this->user->save();
        $this->user->savePermissions(data_get($this->inputs, 'permissions'));

        if ($this->user->wasRecentlyCreated) $this->emit('userCreated');
        else $this->emit('userUpdated');

        $this->close();
    }
}