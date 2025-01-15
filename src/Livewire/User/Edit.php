<?php

namespace Jiannius\Atom\Livewire\User;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Edit extends Component
{
    use AtomComponent;

    public $user;
    public $inputs;

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

    public function open($data = []) : void
    {
        $id = get($data, 'id');

        $this->user = $id
            ? model('user')->withTrashed()->find($id)
            : model('user')->fill($data);

        $this->fill([
            'inputs.password' => null,
            'inputs.is_root' => $this->user->exists ? $this->user->isTier('root') : tier('root'),
            'inputs.is_blocked' => !empty($this->user->blocked_at),
        ]);

        $this->refresh();
    }

    public function close() : void
    {
        $this->commandTo('atom.user', 'refresh');
        Atom::modal('atom.user.edit')->close();
    }

    public function trash() : void
    {
        if ($this->user->id === user('id')) {
            Atom::alert([
                'title' => 'unable-to-delete-user',
                'message' => 'you-cannot-delete-yourself',
            ], 'error');
        }
        else {
            $this->user->delete();
            $this->close();
        }
    }

    public function delete() : void
    {
        $this->user->forceDelete();
        $this->close();
    }

    public function restore() : void
    {
        $this->user->restore();
        $this->close();
    }

    public function submit() : void
    {
        $this->validate();

        $this->user->fill([
            'blocked_at' => get($this->inputs, 'is_blocked')
                ? ($this->user->blocked_at ?? now())
                : null,
        ]);

        if ($password = get($this->inputs, 'password')) {
            $this->user->forceFill(['password' => bcrypt($password)]);
        }
        
        $this->user->save();
        $this->close();
    }
}
