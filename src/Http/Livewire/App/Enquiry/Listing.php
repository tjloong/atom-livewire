<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

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
        breadcrumbs()->home('Enquiries');
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('enquiry')->filter($this->filters);
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Date',
                'sort' => 'created_at',
                'date' => $query->created_at,
            ],
            [
                'name' => 'Name',
                'sort' => 'name',
                'label' => $query->name,
                'href' => route('app.enquiry.update', [$query->id]),
            ],
            [
                'name' => 'Phone',
                'label' => $query->phone,
            ],
            [
                'name' => 'Email',
                'label' => $query->email,
            ],
            [
                'name' => 'Status',
                'status' => $query->status,
            ],
        ];
    }

    /**
     * Export
     */
    public function export(): mixed
    {
        return excel(
            $this->query->get(), 
            ['filename' => 'enquiries-'.time()],
            fn($enquiry) => [
                'Date' => $enquiry->created_at->toDatetimeString(),
                'Name' => $enquiry->name,
                'Phone' => $enquiry->phone,
                'Email' => $enquiry->email,
                'Message' => $enquiry->message,
                'Status' => $enquiry->status,
            ]
        );
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.enquiry.listing');
    }
}