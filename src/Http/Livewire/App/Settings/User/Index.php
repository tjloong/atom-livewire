<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithLoginMethods;
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
        $this->emitTo('app.settings.user.form', 'open', $id);
    }

    // empty trashed
    public function emptyTrashed(): void
    {
        (clone $this->query)->onlyTrashed()->forceDelete();

        $this->popup('Trash Cleared');
        $this->reset('filters');
        $this->emit('refresh');
    }
}