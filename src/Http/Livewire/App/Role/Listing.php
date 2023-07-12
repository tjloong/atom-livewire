<?php

namespace Jiannius\Atom\Http\Livewire\App\Role;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
    ];

    protected $listeners = ['refresh' => '$refresh'];

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('role')
            ->readable()
            ->withCount('users')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->orderBy('name'));
    }

    // update or create
    public function updateOrCreate($id = null): void
    {
        $this->emitTo(atom_lw('app.role.form'), 'open', $id);
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.role.listing');
    }
}