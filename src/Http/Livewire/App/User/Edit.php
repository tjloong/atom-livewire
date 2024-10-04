<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $user;
    public $inputs;

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

    // open
    public function open($data = []) : void
    {
        $id = get($data, 'id');

        if (
            $this->user = $id
            ? model('user')->withTrashed()->find($id)
            : model('user')->fill($data)
        ) {
            $this->fill([
                'inputs.password' => null,
                'inputs.is_root' => $this->user->exists ? $this->user->isTier('root') : tier('root'),
                'inputs.is_blocked' => !empty($this->user->blocked_at),
            ]);
        }
    }

    // close
    public function close() : void
    {
        $this->resetValidation();
        $this->emit('userSaved');
        Atom::modal('edit-user')->close();
    }

    // trash
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

    // delete
    public function delete() : void
    {
        $this->user->forceDelete();
        $this->close();
    }

    // restore
    public function restore() : void
    {
        $this->user->restore();
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

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