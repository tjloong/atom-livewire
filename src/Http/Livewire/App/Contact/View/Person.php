<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\View;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Person extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $contact;
    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = [
        'search' => null,
    ];

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get persons property
     */
    public function getPersonsProperty()
    {
        return $this->contact->persons()
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.view.person');
    }
}