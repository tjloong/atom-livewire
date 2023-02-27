<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class View extends Component
{
    use AuthorizesRequests;

    public $tab;
    public $contact;

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Mount
     */
    public function mount($contactId)
    {
        $this->authorize('contact.view');

        $this->contact = model('contact')->readable()->findOrFail($contactId);
        $this->tab = $this->tab ?? data_get(tabs($this->tabs)->first(), 'slug');

        breadcrumbs()->push($this->contact->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return array_filter([
            $this->contact->type === 'company' 
                ? ['slug' => 'person', 'label' => 'Contact Persons', 'livewire' => 'app.contact.person.listing'] 
                : null,
        ]);
    }

    /**
     * Get fields property
     */
    public function getFieldsProperty()
    {
        return [
            ['label' => 'Type', 'value' => str($this->contact->type)->title()],
            ['label' => 'Email', 'value' => $this->contact->email ?? '--'],
            ['label' => 'Phone', 'value' => $this->contact->phone ?? '--'],
            ['label' => 'Fax', 'value' => $this->contact->fax ?? '--'],
            ['label' => 'BRN', 'value' => $this->contact->brn ?? '--'],
            ['label' => 'Tax Number', 'value' => $this->contact->tax_number ?? '--'],
            ['label' => 'Website', 'value' => $this->contact->website ?? '--'],
            ['label' => 'Address', 'value' => format_address($this->contact) ?? '--'],
        ];
    }

    /**
     * Get livewire property
     */
    public function getLivewireProperty()
    {
        $tab = tabs($this->tabs)->firstWhere('slug', $this->tab);
        $livewire = data_get($tab, 'livewire');

        return $livewire;
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->contact->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.view');
    }
}