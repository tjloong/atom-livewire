<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort;

    public $filters = ['search' => null];

    // get pages property
    public function getPagesProperty()
    {
        return model('page')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest())
            ->get();
    }

    // render
    public function render()
    {
        return atom_view('app.page.listing');
    }
}