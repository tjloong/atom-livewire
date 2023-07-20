<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Role;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
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
        $this->emitTo('app.settings.role.form', 'open', $id);
    }
}