<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Person;

use Illuminate\Contracts\Database\Eloquent\Builder;
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
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('contact_person')
            ->when($this->contact, fn($q) => $q->where('contact_id', $this->contact->id))
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Name',
                'sort' => 'name',
                'label' => collect([$query->salutation, $query->name])->filter()->join(' '),
                'small' => $query->designation,
                'href' => route('app.contact.person.view', [$query->id]),
            ],

            [
                'name' => 'Contact',
                'label' => $query->email,
                'small' => $query->phone,
            ],

            [
                'name' => 'Created Date',
                'sort' => 'created_at',
                'date' => $query->created_at,
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.contact.person.listing');
    }
}