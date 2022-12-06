<?php

namespace Jiannius\Atom\Http\Livewire\App\Document;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use WithTable;

    public $type;
    public $contact;
    public $fullpage;
    public $sortBy = 'issued_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
        ]],
        'page' => ['except' => 1],
        'sortBy' => ['except' => 'issued_at'],
        'sortOrder' => ['except' => 'desc'],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        $this->authorize($this->type.'.view');

        if ($this->fullpage = current_route('app.document.listing')) {
            breadcrumbs()->home($this->title);
        }
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return str($this->type)->headline()->plural()->toString();
    }

    /**
     * Get documents property
     */
    public function getDocumentsProperty()
    {
        return model('document')
            ->when(
                model('document')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->when(
                $this->contact,
                fn($q) => $q->where('contact_id', $this->contact->id),
            )
            ->where('documents.type', $this->type)
            ->filter($this->filters)
            ->join('contacts', 'contacts.id', '=', 'documents.contact_id')
            ->join('users', 'users.id', '=', 'documents.owned_by')
            ->select('documents.*')
            ->orderBy($this->sortBy, $this->sortOrder)
            ->latest('id')
            ->paginate($this->maxRows);
    }

    /**
     * Get preferences route property
     */
    public function getPreferencesRouteProperty()
    {
        //
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.listing');
    }
}