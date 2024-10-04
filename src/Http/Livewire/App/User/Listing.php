<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Component;

class Listing extends Component
{
    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $listeners = [
        'userSaved' => '$refresh',
    ];

    // mount
    public function mount() : void
    {
        $this->fill(['filters.status' => enum('user-status', 'ACTIVE')->value]);
    }

    public function getQueryProperty() : mixed
    {
        return model('user');
    }

    // get users property
    public function getUsersProperty() : mixed
    {
        return $this->getTable(
            query: $this->query,
        );
    }

    // get trashed property
    public function getTrashedProperty() : mixed
    {
        return $this->query->onlyTrashed()->count();
    }

    // empty trashed
    public function emptyTrashed() : void
    {
        $id = get($this->table, 'checkboxes');

        $this->query
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