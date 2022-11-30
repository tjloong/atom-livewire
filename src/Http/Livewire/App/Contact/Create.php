<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Livewire\Component;

class Create extends Component
{
    public $contact;

    /**
     * Mount
     */
    public function mount($type = null)
    {
        $this->contact = model('contact')->fill([
            'type' => $type,
            'country' => 'MY',
        ]);

        breadcrumbs()->push('Create '.str()->title($type));
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.create');
    }
}