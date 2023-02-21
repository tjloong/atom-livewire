<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Person;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;
    use WithTable;

    public $contact;
    public $sort = 'name,asc';
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
            ->paginate($this->maxRows)
            ->through(fn($person) => [
                [
                    'column_name' => 'Name',
                    'column_sort' => 'name',
                    'label' => collect([$person->salutation, $person->name])->filter()->join(' '),
                    'small' => $person->designation,
                    'href' => route('app.contact.person.view', [$this->contact->id, $person->id]),
                ],

                [
                    'column_name' => 'Contact',
                    'label' => $person->email,
                    'small' => $person->phone,
                ],

                [
                    'column_name' => 'Created Date',
                    'column_sort' => 'created_at',
                    'date' => $person->created_at,
                ],
            ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.person.listing');
    }
}