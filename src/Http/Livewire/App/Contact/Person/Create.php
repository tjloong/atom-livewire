<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Person;

use Livewire\Component;

class Create extends Component
{
    public $contact;
    public $person;

    /**
     * Mount
     */
    public function mount($contactId)
    {
        $this->contact = model('contact')->when(
            model('contact')->enabledHasTenantTrait,
            fn($q) => $q->belongsToTenant(),
        )->findOrFail($contactId);

        $this->person = model('contact_person')->fill([
            'contact_id' => $this->contact->id,
        ]);

        breadcrumbs()->push('Create Contact Person');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.person.create');
    }
}