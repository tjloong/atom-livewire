<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $sort = 'name,asc';
    public $filters = [
        'search' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
        ]],
    ];

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('role')
            ->readable()
            ->when(enabled_module('permissions'), fn($q) => $q->withCount('permissions'))
            ->withCount('users')
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return array_filter([
            [
                'column_name' => 'Role',
                'column_sort' => 'name',
                'label' => $query->name,
                'href' => route('app.role.update', [$query->id]),
            ],

            enabled_module('permissions') ? [
                'column_name' => 'Permissions',
                'column_class' => 'text-right',
                'class' => 'text-right',
                'count' => in_array($query->slug, ['admin', 'administrator'])
                    ? '∞'
                    : $query->permissions_count,
                'uom' => 'permission',
            ] : null,

            [
                'column_name' => 'Users',
                'count' => $query->users_count,
                'uom' => 'user',
                'href' => route('app.settings', [
                    'tab' => 'user',
                    'filters' => ['is_role' => $query->slug],
                ]),
            ],
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.role.listing');
    }
}