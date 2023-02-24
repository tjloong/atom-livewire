<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort = 'name,asc';
    public $filters = ['search' => null];

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
        return model('team')
            ->readable()
            ->withCount('users')
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Team',
                'column_sort' => 'name',
                'label' => $query->name,
                'href' => route('app.team.update', [$query->id]),
            ],

            [
                'column_name' => 'Members',
                'count' => $query->users_count,
                'uom' => 'member',
                'href' => route('app.settings', [
                    'tab' => 'user',
                    'filters' => ['in_team' => $query->id],
                ]),
            ],
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.team.listing');
    }
}