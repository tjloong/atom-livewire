<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Livewire\Component;

class Create extends Component
{
    public $category;
    public $contact;

    /**
     * Mount
     */
    public function mount()
    {
        $this->contact = model('contact')->fill([
            'category' => $this->category,
            'type' => 'person',
            'country' => 'MY',
        ]);

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return 'Create '.str($this->contact->category)->title()->toString();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.create');
    }
}