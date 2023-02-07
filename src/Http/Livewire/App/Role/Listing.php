<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = [
        'search' => null,
    ];

    /**
     * Get roles property
     */
    public function getRolesProperty()
    {
        return model('role')
            ->when(
                model('role')->enabledHasTenantTrait,
                fn($q) => $q->belongsToTenant(),
            )
            ->when(enabled_module('permissions'), fn($q) => $q->withCount('permissions'))
            ->withCount('users')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows)
            ->through(fn($role) => array_filter([
                [
                    'column_name' => 'Role',
                    'column_sort' => 'name',
                    'label' => $role->name,
                    'href' => route('app.role.update', [$role->id]),
                ],

                enabled_module('permissions') ? [
                    'column_name' => 'Permissions',
                    'count' => $role->permissions_count,
                    'uom' => 'permission',
                    'href' => route('app.permission.role', [$role->id]),
                ] : null,

                [
                    'column_name' => 'Users',
                    'count' => $role->users_count,
                    'uom' => 'user',
                    'href' => route('app.settings', [
                        'tab' => 'system/user',
                        'filters' => ['role_id' => $role->id],
                    ]),
                ],
            ]));
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.role.listing');
    }
}