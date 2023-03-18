<?php

namespace Jiannius\Atom\Http\Livewire\App\Document;

use Illuminate\Contracts\Database\Eloquent\Builder;
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
        'filters',
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        if (!in_array($this->type, model('document')->types)) abort(404);
        
        $this->authorize($this->type.'.view');

        if ($this->fullpage = current_route('app.document.listing')) {
            breadcrumbs()->home($this->title);
        }
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return str($this->type)->headline()->plural()->toString();
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
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
    public function getTableColumns($query): array
    {
        return array_filter([
            [
                'name' => 'Date',
                'sort' => 'issued_at',
                'date' => $query->issued_at,
            ],

            [
                'name' => 'Number',
                'sort' => 'number',
                'label' => $query->number,
                'href' => route('app.document.view', [$query->id]),
            ],

            $this->contact ? null : [
                'name' => in_array($this->type, ['purchase-order', 'bill']) ? 'Vendor' : 'Client',
                'sort' => 'contacts.name',
                'label' => $query->contact->name,
                'href' => route('app.document.view', [$query->id]),
                'small' => str()->limit($query->summary, 100),
            ],

            $this->type === 'delivery-order' ? null : [
                'name' => 'Amount',
                'sort' => 'grand_total',
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
                'name' => 'Status',
                'class' => 'text-right',
            ],

            [
                'name' => 'Owner',
                'sort' => 'users.name',
            ],
        ]);
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
        return atom_view('app.document.listing');
    }
}