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
    public function mount($contactId, $personId)
    {
        $this->contact = model('contact')->readable()->findOrFail($contactId);
        $this->person = $this->contact->persons()->findOrFail($personId);

        breadcrumbs()->push($this->person->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->person->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.person.view');
    }
}