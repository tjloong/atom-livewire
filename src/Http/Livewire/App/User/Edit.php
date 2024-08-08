<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $user;
    public $inputs;

    protected $listeners = [
        'editUser' => 'open',
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

    // open
    public function open($data = []) : void
    {
        $id = get($data, 'id');

        if (
            $this->user = $id
            ? model('user')->withTrashed()->find($id)
            : model('user')->fill($data)
        ) {
            $this->resetValidation();

            $this->fill([
                'inputs.password' => null,
                'inputs.is_root' => $this->user->exists ? $this->user->isTier('root') : tier('root'),
                'inputs.is_blocked' => !empty($this->user->blocked_at),
            ]);

            $this->overlay();
        }
    }

    // trash
    public function trash() : void
    {
        if ($this->user->id === user('id')) {
            $this->popup([
                'title' => 'app.label.unable-to-delete-user',
                'message' => 'app.label.you-cannot-delete-yourself',
            ], 'alert', 'error');
        }
        else {
            $this->user->delete();
            $this->overlay(false);
        }
    }

    // delete
    public function delete() : void
    {
        $this->user->forceDelete();
        $this->overlay(false);
    }

    // restore
    public function restore() : void
    {
        $this->user->restore();
        $this->overlay(false);
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
        $this->overlay(false);
    }
}