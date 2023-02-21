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

        $this->contact = model('contact')
            ->when(
                model('contact')->enabledHasTenantTrait,
                fn($q) => $q->belongsToTenant(),
            )
            ->findOrFail($contactId);

        $this->tab = $this->tab ?? data_get(collect($this->tabs)->first(), 'slug');

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
                : [],
        ]);
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