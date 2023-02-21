<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $category;
    public $sort = 'created_at,desc';
    public $filters = [
        'search' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
        ]],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return str($this->category)->plural()->title()->toString();
    }

    /**
     * Get contacts property
     */
    public function getContactsProperty()
    {
        return model('contact')
            ->when(
                model('contact')->enabledHasTenantTrait,
                fn($q) => $q->belongsToTenant(),
            )
            ->where('category', $this->category)
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows)
            ->through(fn($contact) => [
                [
                    'column_name' => 'Contact',
                    'column_sort' => 'name',
                    'label' => $contact->name,
                    'href' => route('app.contact.view', [$contact->id]),
                    'avatar' => optional($contact->avatar)->url,
                    'small' => empty($small) ? __('No contact number') : $small,
                ],
                [
                    'column_name' => 'Created Date',
                    'column_sort' => 'created_at',
                    'column_class' => 'text-right',
                    'date' => $contact->created_at,
                ],
            ]);
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
        return atom_view('app.contact.listing');
    }
}