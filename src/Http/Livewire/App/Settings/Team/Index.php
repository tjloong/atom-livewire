<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Team;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Index extends Component
{
    use WithTable;

    public $sort = 'name,asc';
    public $filters = ['search' => null];

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('team')
            ->readable()
            ->withCount('users')
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Team',
                'sort' => 'name',
                'label' => $query->name,
                'href' => route('app.team.update', [$query->id]),
            ],

            [
                'name' => 'Members',
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
    public function render(): mixed
    {
        return atom_view('app.settings.team');
    }
}