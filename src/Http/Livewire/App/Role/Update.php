<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\User;
use Jiannius\Atom\Models\Role;

class Update extends Component
{
    public Role $role;
    public $scopes;
    public $search;
    public $readonly;

    protected $messages = [
        'role.name.required' => 'Role name is required.',
        'role.name.unique' => 'There is another role with the same name.',
        'role.scope.required' => 'Please select a scope.',
    ];

    protected function rules()
    {
        return [
            'role.name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->role),
            ],
            'role.scope' => 'required',    
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumb($this->role->name);

        $this->scopes = $this->getScopes();
        $this->readonly = $this->role->exists && $this->role->is_system;
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return User::query()
            ->when($this->search, fn($q) => $q->search($this->search))
            ->where('role_id', $this->role->id)
            ->orderBy('name');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->role->save();
        
        $this->dispatchBrowserEvent('toast', ['message' => 'Role Updated', 'type' => 'success']);
    }

    /**
     * Duplicate
     */
    public function duplicate()
    {
        $newrole = Role::create([
            'name' => $this->role->name . ' Copy',
            'scope' => $this->role->scope,
        ]);

        $newrole->abilities()->sync($this->role->abilities->pluck('id')->toArray());

        session()->flash('flash', 'Role Duplicated::success');

        return redirect()->route('role.update', [$newrole]);
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($this->role->users()->count() > 0) {
            $this->dispatchBrowserEvent('alert', [
                'title' => 'Unable to Delete', 
                'message' => 'This role has users assigned to it.', 
                'type' => 'error',
            ]);
        }
        else {
            $this->role->delete();
            session()->flash('flash', 'Role deleted');
            return redirect()->route('role.listing');
        }
    }

    /**
     * Get scopes
     */
    private function getScopes()
    {
        $scopes = auth()->user()->isRole('root')
            ? ['root', 'global', 'restrict']
            : ['global', 'restrict'];

        return collect($scopes)->map(fn($scope) => Role::getScopeDescription($scope));
    }

    /**
     * Get assignable users
     */
    public function getAssignableUsers($page, $text = null)
    {
        return User::query()
            ->when($text, fn($q) => $q->search($text))
            ->where('id', '<>', auth()->id())
            ->paginate(30, ['*'], 'page', $page)
            ->toArray();
    }

    /**
     * Assign user to role
     */
    public function assignUser($id)
    {
        $user = User::find($id);
        $user->role_id = $this->role->id;
        $user->saveQuietly();

        $this->dispatchBrowserEvent('toast', ['message' => 'User assigned to role', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.role.update', [
            'users' => $this->users->get(),
        ]);
    }
}