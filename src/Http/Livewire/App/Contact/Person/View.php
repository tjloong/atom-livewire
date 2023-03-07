<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Person;

use Livewire\Component;

class View extends Component
{
    public $contact;
    public $person;

    /**
     * Mount
     */
    public function mount($personId): void
    {
        $this->person = model('contact_person')
            ->whereHas('contact', fn($q) => $q->readable())
            ->findOrFail($personId);

        breadcrumbs()->push($this->person->name);
    }

    /**
     * Get fields property
     */
    public function getFieldsProperty(): array
    {
        return [
            'Company' => $this->person->contact->name,
            'Name' => collect([$this->person->salutation, $this->person->name])->filter()->join(' '),
            'Email' => $this->person->email ?? '--',
            'Phone' => $this->person->phone ?? '--',
            'Designation' => $this->person->designation ?? '--',
        ];
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->person->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.contact.person.view');
    }
}