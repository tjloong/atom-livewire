<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;

class Update extends Component
{
    use WithForm;
    use WithLoginMethods;

    public $user;
    public $inputs;

    protected $listeners = [
        'createUser' => 'open',
        'updateUser' => 'open',
    ];

    // validation
    protected function validation() : array
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

    // get permissions property
    public function getPermissionsProperty() : mixed
    {
        if (!has_table('permissions')) return null;

        return collect(model('permission')->getPermissionList())->mapWithKeys(fn($actions, $module) => [
            $module => collect($actions)->mapWithKeys(fn($action) => [
                $action => tier('root') || $this->user->permissions()
                    ->where('permission', $module.'.'.$action)
                    ->count(),
            ])
        ]);
    }

    // open
    public function open($id = null) : void
    {
        $this->resetValidation();

        if ($this->user = $id
            ? model('user')->readable()->withTrashed()->find($id)
            : model('user')
        ) {
            $this->fill([
                'inputs.password' => null,
                'inputs.is_root' => $this->user->exists ? $this->user->isTier('root') : tier('root'),
                'inputs.is_blocked' => !empty($this->user->blocked_at),
            ]);

            $this->dispatchBrowserEvent('user-update-open');
        }
    }

    // close
    public function close() : void
    {
        $this->dispatchBrowserEvent('user-update-close');
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

    // toggle permission
    public function togglePermission($module, $action) : void
    {
        $key = $module.'.'.$action;

        if ($permission = $this->user->permissions->firstWhere('permission', $key)) {
            $permission->delete();
        }
        else {
            $this->user->permissions()->create(['permission' => $key]);
        }
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

        if ($this->user->wasRecentlyCreated) $this->emit('userCreated');
        else $this->emit('userUpdated');

        $this->close();
    }
}