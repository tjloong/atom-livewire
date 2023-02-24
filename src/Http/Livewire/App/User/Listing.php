<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $params; // when using this as child component
    public $sort = 'created_at,desc';
    public $filters = [
        'search' => null,
        'status' => null,
        'is_role' => null,
        'in_team' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
            'status' => null,
            'is_role' => null,
            'in_team' => null,    
        ]],
    ];

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('user')
            ->readable()
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Name',
                'column_sort' => 'name',
                'label' => $query->name.($query->id === user()->id ? ' ('.__('You').')' : ''),
                'href' => $query->id === user()->id 
                    ? route('app.settings', ['login'])
                    : route('app.user.update', [$query->id]),
                'small' => $query->email,
            ],

            enabled_module('roles') ? [
                'column_name' => 'Role',
                'label' => $query->role->name ?? '--',
            ] : null,

            [
                'column_name' => 'Status',
                'column_class' => 'text-right',
                'status' => array_filter([
                    $query->is_root ? 'root' : null, 
                    $query->status,
                ]),
                'class' => 'text-right',
            ],

            [
                'column_name' => 'Created Date',
                'column_sort' => 'created_at',
                'column_class' => 'text-right',
                'date' => $query->created_at,
                'class' => 'text-right',
            ],

            [
                'column_name' => 'Last Active',
                'column_sort' => 'last_active_at',
                'column_class' => 'text-right',
                'datetime' => $query->last_active_at,
                'class' => 'text-right',
            ],
        ];
    }

    /**
     * Empty trashed
     */
    public function emptyTrashed()
    {
        (clone $this->query)->onlyTrashed()->forceDelete();

        $this->popup('Trash Cleared');
        $this->reset('filters');
        $this->emit('refresh');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.user.listing');
    }
}