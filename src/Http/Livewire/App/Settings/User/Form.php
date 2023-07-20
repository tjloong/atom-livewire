<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Form extends Component
{
    use WithForm;
    use WithLoginMethods;
    use WithPopupNotify;

    public $user;
    public $inputs;

    protected $listeners = ['open'];

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
                'user.is_root' => ['nullable'],
            ],

            $this->isLoginMethod('email') ? [
                'user.email' => [
                    'required_without:user.username' => 'Login email is required.',
                    function ($attr, $value, $fail) {
                        if (model('user')->where('email', $value)->where('id', '<>', $this->user->id)->count()) {
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
                        if (model('user')->where('username', $value)->where('id', '<>', $this->user->id)->count()) {
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
    public function open($data = null): void
    {
        $id = is_numeric($data) ? $data : data_get($data, 'id');

        $this->user = $id 
            ? model('user')->readable()->withTrashed()->findOrFail($id)
            : model('user')->fill(['is_root' => tier('root')]);

        $this->fill([
            'inputs.password' => null,
            'inputs.is_blocked' => !empty($this->user->blocked_at),
        ]);

        if (has_table('teams')) {
            $this->fill([
                'inputs.teams' => $this->user->teams->pluck('id')->toArray(),
            ]);
        }

        $this->resetValidation();
        $this->dispatchBrowserEvent('user-form-open');
    }

    // delete
    public function delete(): void
    {
        if ($this->user->id === user('id')) {
            $this->popup([
                'title' => 'Unable To Delete User',
                'message' => 'You cannot delete yourself.',
            ], 'alert', 'error');
        }
        else {
            if ($this->user->trashed()) $this->user->forceDelete();
            else $this->user->delete();

            $this->close();
        }
    }

    // block
    public function block(): void
    {
        if ($this->user->id === user('id')) {
            $this->popup([
                'title' => 'Unable To Block User',
                'message' => 'You cannot block yourself.',
            ], 'alert', 'error');
        }
        else {
            $this->user->block();
            $this->close();
        }
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        $this->user->fill([
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

    // close
    public function close(): void
    {
        $this->reset(['user', 'inputs']);
        $this->emit('refresh');
        $this->dispatchBrowserEvent('user-form-close');
    }
}