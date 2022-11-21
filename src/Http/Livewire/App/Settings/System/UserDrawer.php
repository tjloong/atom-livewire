<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Livewire\Component;

class UserDrawer extends Component
{
    public $role;
    public $team;

    protected $listeners = [
        'open',
        'refresh' => '$refresh',
    ];

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        if (!$this->role && !$this->team) return [];

        return model('user')
            ->belongsToAccount()
            ->when($this->role, fn($q) => $q->where('role_id', $this->role->id))
            ->when($this->team, fn($q) => $q->whereHas('teams', fn($q) => $q->where('teams.id', $this->team->id)))
            ->get();
    }

    /**
     * Get users for select property
     */
    public function getUsersForSelectProperty()
    {
        return model('user')
            ->belongsToAccount()
            ->where('id', '<>', auth()->user()->id)
            ->orderBy('name')
            ->get()
            ->map(fn($user) => [
                'value' => $user->id,
                'label' => $user->name,
                'small' => $user->email,
            ]);
    }

    /**
     * Open
     */
    public function open($data)
    {
        $this->role = data_get($data, 'role_id') ? model('role')->findOrFail(data_get($data, 'role_id')) : null;
        $this->team = data_get($data, 'team_id') ? model('team')->findOrFail(data_get($data, 'team_id')) : null;

        $this->dispatchBrowserEvent('user-drawer-open');
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        $this->emitTo(lw('app.settings.system.user-form-modal'), 'open', $id);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.system.user-drawer');
    }
}