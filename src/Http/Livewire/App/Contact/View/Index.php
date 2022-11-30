<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\View;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPopupNotify;

    public $tab = 'person';
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
                model('contact')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->findOrFail($contactId);

        breadcrumbs()->push($this->contact->name);
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