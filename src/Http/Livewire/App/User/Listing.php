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
        'role_id' => null,
        'team_id' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
            'status' => null,
            'role_id' => null,
            'team_id' => null,    
        ]],
    ];

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('user')
            ->when(!user()->isTier('root'), fn($q) => $q->tier())
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return (clone $this->query)
            ->paginate($this->maxRows)
            ->through(fn($user) => array_filter([
                [
                    'column_name' => 'Name',
                    'column_sort' => 'name',
                    'label' => $user->name.($user->id === user()->id ? ' ('.__('You').')' : ''),
                    'href' => $user->id === user()->id ? null : route('app.user.update', [$user->id]),
                    'small' => $user->email,
                ],

                enabled_module('roles') ? [
                    'column_name' => 'Role',
                    'label' => $user->role->name ?? '--',
                ] : null,

                [
                    'column_name' => 'Status',
                    'column_class' => 'text-right',
                    'status' => array_filter([
                        $user->is_root ? 'root' : null, 
                        $user->status,
                    ]),
                    'class' => 'text-right',
                ],

                [
                    'column_name' => 'Created Date',
                    'column_sort' => 'created_at',
                    'column_class' => 'text-right',
                    'date' => $user->created_at,
                    'class' => 'text-right',
                ],

                [
                    'column_name' => 'Last Active',
                    'column_sort' => 'last_active_at',
                    'column_class' => 'text-right',
                    'datetime' => $user->last_active_at,
                    'class' => 'text-right',
                ],
            ]));
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