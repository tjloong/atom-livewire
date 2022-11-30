<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
    use WithTable;

    public $type;
    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => null,
    ];

    /**
     * Mount
     */
    public function mount($type)
    {
        $this->type = $type;

        breadcrumbs()->home(str()->title(str()->plural($type)));
    }

    /**
     * Get contacts property
     */
    public function getContactsProperty()
    {
        return model('contact')
            ->when(
                model('contact')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->where('type', $this->type)
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows)
            ->through(fn($contact) => [
                [
                    'column_name' => 'Contact',
                    'column_sort' => 'name',
                    'label' => $contact->name,
                    'href' => route('app.contact.view', [$contact->id]),
                    'avatar' => optional($contact->logo)->url,
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