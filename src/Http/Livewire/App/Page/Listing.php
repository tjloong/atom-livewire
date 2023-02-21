<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort = 'name,asc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['search' => null], 
    ];

    /**
     * Get pages property
     */
    public function getPagesProperty()
    {
        return model('page')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.page.listing');
    }
}