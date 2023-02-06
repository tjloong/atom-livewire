<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\View;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPopupNotify;

    public $tab;
    public $contact;

    protected $queryString = ['tab'];
    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Mount
     */
    public function mount($contactId)
    {
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
        return [
            ['slug' => 'person', 'label' => 'Contact Persons'],
        ];
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->contact->delete();
        return redirect()->route('app.contact.listing', [$this->contact->type])->with('info', 'Contact Deleted');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.view');
    }
}