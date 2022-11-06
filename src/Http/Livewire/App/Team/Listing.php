<?php

namespace Jiannius\Atom\Http\Livewire\App\Team;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
    use WithTable;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['except' => ['search' => null]],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Get teams property
     */
    public function getTeamsProperty()
    {
        return model('team')
            ->when(model('team')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->withCount('users')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.team.listing');
    }
}