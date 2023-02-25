<?php

namespace Jiannius\Atom\Http\Livewire\App\Document;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use AuthorizesRequests;
    use WithTable;

    public $type;
    public $contact;
    public $fullpage;
    public $sort = 'issued_at,desc';
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
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('document')
            ->readable()
            ->when(
                $this->contact,
                fn($q) => $q->where('contact_id', $this->contact->id),
            )
            ->where('documents.type', $this->type)
            ->filter($this->filters)
            ->join('contacts', 'contacts.id', '=', 'documents.contact_id')
            ->join('users', 'users.id', '=', 'documents.owned_by')
            ->select('documents.*');
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return array_filter([
            [
                'column_name' => 'Date',
                'column_sort' => 'issued_at',
                'date' => $query->issued_at,
            ],

            [
                'column_name' => 'Number',
                'column_sort' => 'number',
                'label' => $query->number,
                'href' => route('app.document.view', [$query->id]),
            ],

            $this->contact ? null : [
                'column_name' => in_array($this->type, ['purchase-order', 'bill']) ? 'Vendor' : 'Client',
                'column_sort' => 'contacts.name',
                'label' => $query->contact->name,
                'href' => route('app.document.view', [$query->id]),
                'small' => str()->limit($query->summary, 100),
            ],

            $this->type === 'delivery-order' ? null : [
                'column_name' => 'Amount',
                'column_sort' => 'grand_total',
                'column_class' => 'text-right',
                'class' => 'text-right',
                'label' => $query->is_splitted
                    ? currency($query->splitted_total, $query->currency)
                    : currency($query->grand_total, $query->currency),
                'small' => $query->is_foreign_currency
                    ? currency(
                        $query->is_splitted
                            ? $query->calculateCurrencyConversion('splitted_total')
                            : $query->calculateCurrencyConversion('grand_total'),
                        $query->master_currency
                    )
                    : null,
            ],

            [
                'column_name' => 'Status',
                'column_class' => 'text-right',
                'class' => 'text-right',
            ],

            [
                'column_name' => 'Owner',
                'column_sort' => 'users.name',
                'column_class' => 'text-right',
                'class' => 'text-right',
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
        return atom_view('app.document.listing');
    }
}