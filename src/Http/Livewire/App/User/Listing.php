<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $sort = 'created_at,desc';

    public $filters = [
        'search' => null,
        'status' => null,
        'is_role' => null,
        'in_team' => null,
    ];

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('user')
            ->readable()
            ->filter($this->filters);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'status' => collect(['active', 'inactive', 'blocked', 'trashed'])->map(fn($val) => [
                'value' => $val, 
                'label' => ucfirst($val),
            ])->toArray(),

            'roles' => enabled_module('roles')
                ? model('role')->readable()->get()->map(fn($role) => [
                    'value' => $role->slug,
                    'label' => $role->name,
                ])->toArray()
                : null,

            'teams' => enabled_module('teams')
                ? model('team')->readable()->get()->map(fn($team) => [
                    'value' => (string)$team->id,
                    'label' => $team->name,
                ])->toArray()
                : null,
        ];
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Name',
                'sort' => 'name',
                'label' => $query->name.($query->id === user()->id ? ' ('.__('You').')' : ''),
                'href' => $query->id === user()->id 
                    ? route('app.settings', ['login'])
                    : route('app.user.update', [$query->id]),
                'small' => $query->email,
            ],

            enabled_module('roles') ? [
                'name' => 'Role',
                'label' => $query->role->name ?? '--',
            ] : null,

            [
                'name' => 'Status',
                'status' => array_filter([
                    $query->is_root ? 'root' : null, 
                    $query->status,
                ]),
                'class' => 'text-right',
            ],

            [
                'name' => 'Created Date',
                'sort' => 'created_at',
                'date' => $query->created_at,
                'class' => 'text-right',
            ],

            [
                'name' => 'Last Active',
                'sort' => 'last_active_at',
                'datetime' => $query->last_active_at,
                'class' => 'text-right',
            ],
        ];
    }

    /**
     * Empty trashed
     */
    public function emptyTrashed(): void
    {
        (clone $this->query)->onlyTrashed()->forceDelete();

        $this->popup('Trash Cleared');
        $this->reset('filters');
        $this->emit('refresh');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.listing');
    }
}