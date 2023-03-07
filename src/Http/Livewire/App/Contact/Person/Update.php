<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Person;

use Livewire\Component;

class Update extends Component
{
    public $person;

    /**
     * Mount
     */
    public function mount($personId): void
    {
        $this->person = model('contact_person')
            ->whereHas('contact', fn($q) => $q->readable())
            ->findOrFail($personId);

        breadcrumbs()->push('Update');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.contact.person.update');
    }
}