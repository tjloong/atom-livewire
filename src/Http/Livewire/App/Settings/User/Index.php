<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Index extends Component
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

    protected $listeners = ['refresh' => '$refresh'];

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('user')
            ->readable()
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // update or create
    public function updateOrCreate($id = null): void
    {
        $this->emitTo(atom_lw('app.settings.user.form'), 'open', $id);
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
        return atom_view('app.settings.user');
    }
}