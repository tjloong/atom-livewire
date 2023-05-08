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
                'href' => route('app.user.update', [$query->id]),
            ],

            [
                'name' => 'Email',
                'label' => $query->email,
            ],

            enabled_module('roles') ? [
                'name' => 'Role',
                'label' => $query->role->name ?? '--',
            ] : null,

            tenant() ? [
                'status' => $query->isTenantOwner() ? ['yellow' => 'owner'] : null,
            ] : null,
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