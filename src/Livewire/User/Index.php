<?php

namespace Jiannius\Atom\Livewire\User;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Index extends Component
{
    use AtomComponent;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    public function getUsersProperty() : mixed
    {
        return $this->getTable(
            query: model('user')->query(),
        );
    }

    public function getTrashedProperty() : mixed
    {
        return model('user')->onlyTrashed()->count();
    }

    public function emptyTrashed() : void
    {
        $id = get($this->table, 'checkboxes');

        model('user')
            ->onlyTrashed()
            ->when($id, fn($q) => $q->whereIn('id', $id))
            ->get()
            ->each(fn($user) => $user->forceDelete());

        $this->fill([
            'table.trashed' => false,
            'table.checkboxes' => [],
        ]);

        Atom::alert('trash-cleared');
    }
}
