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
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('contact')
            ->readable()
            ->where('category', $this->category)
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Contact',
                'column_sort' => 'name',
                'label' => $query->name,
                'href' => route('app.contact.view', [$query->id]),
                'avatar' => optional($query->avatar)->url,
                'small' => empty($query->email_phone) ? __('No contact number') : $query->email_phone,
            ],
            [
                'column_name' => 'Created Date',
                'column_sort' => 'created_at',
                'column_class' => 'text-right',
                'date' => $query->created_at,
            ],
        ];
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