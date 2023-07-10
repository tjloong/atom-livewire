<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
        'is_role' => null,
        'in_team' => null,
    ];

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('user')
            ->readable()
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // get options property
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

    // get table columns
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

    // empty trashed
    public function emptyTrashed(): void
    {
        (clone $this->query)->onlyTrashed()->forceDelete();

        $this->popup('Trash Cleared');
        $this->reset('filters');
        $this->emit('refresh');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.user.listing');
    }
}