<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Livewire\Component;

class Update extends Component
{
    public $contact;

    /**
     * Mount
     */
    public function mount($contactId)
    {
        $this->contact = model('contact')
            ->when(
                model('contact')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->findOrFail($contactId);

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return str()->headline('Update '.$this->contact->type);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.update');
    }
}