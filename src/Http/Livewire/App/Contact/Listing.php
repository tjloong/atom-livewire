<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Illuminate\Contracts\Database\Eloquent\Builder;
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
    public function mount(): void
    {
        breadcrumbs()->home($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return str($this->category)->headline()->plural()->toString();
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('contact')
            ->readable()
            ->where('category', $this->category)
            ->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Contact',
                'sort' => 'name',
                'label' => $query->name,
                'href' => route('app.contact.view', [$query->id]),
                'avatar' => optional($query->avatar)->url,
                'small' => empty($query->email_phone) ? __('No contact number') : $query->email_phone,
            ],
            [
                'name' => 'Created Date',
                'sort' => 'created_at',
                'date' => $query->created_at,
            ],
        ];
    }

    /**
     * Get preferences route property
     */
    public function getPreferencesRouteProperty(): mixed
    {
        return null;
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.contact.listing');
    }
}