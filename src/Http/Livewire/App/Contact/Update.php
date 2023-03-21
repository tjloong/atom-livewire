<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Livewire\Component;

class Update extends Component
{
    public $contact;

    /**
     * Mount
     */
    public function mount($contactId): void
    {
        $this->contact = model('contact')->readable()->findOrFail($contactId);

        breadcrumbs()->push('Update');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.contact.update');
    }
}